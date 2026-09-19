<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Tailor;

class ChatbotController extends Controller
{

public function reply(Request $request)
{
    $request->validate([
        'message' => 'required|string|max:500',
    ]);

    $message = trim($request->message);
    $apiKey = config('services.groq.api_key');

    $models = [
        'openai/gpt-oss-20b',
        'qwen/qwen3.6-27b',
    ];

    if (empty($apiKey)) {
        \Log::error('GROQ_API_KEY is missing or null!');

        return response()->json([
            'success' => false,
            'reply'   => 'Sorry, the AI service is currently unavailable. Please try again later.'
        ], 500);
    }

    try {
        $context = $this->buildContext($message);
    } catch (\Throwable $e) {
        \Log::error('buildContext() failed', [
            'error' => $e->getMessage(),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
        ]);

        $context = 'No additional database information is available.';
    }

    $systemPrompt = "You are Stitch, the helpful assistant for Stitchify - an online tailoring platform in Pakistan.

PLATFORM INFO:
- Customers can browse tailors, place orders, track status, and pay online
- Tailors accept/reject orders, set price and delivery date
- Order statuses: pending → accepted → in_progress → ready → dispatched → delivered

CURRENT DATA FROM DATABASE:
{$context}

Instructions:
- Keep responses SHORT — maximum 3-4 lines only
- No tables, no headers, no bullet lists
- Talk in a friendly, casual way like a real person
- Respond in the same language as the user (English/Urdu/Roman Urdu)
- If user asks about an order, give only the most important info
- Never invent order information
- Only use database information when relevant
- If the information is unavailable, say so clearly
- Never mention internal database/context details
- Get straight to the point";

    $reply = null;
    $successfulModel = null;

    foreach ($models as $model) {

        try {
            \Log::info('Trying Groq model', [
                'model' => $model
            ]);

            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,

                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => $message
                        ],
                    ],

                    'temperature' => 0.6,

                    'max_completion_tokens' => 300,

                    'reasoning_effort' => 'low',
                ]);

            
            \Log::info('Groq API response', [
                'model'  => $model,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {

                $reply = trim((string) $response->json('choices.0.message.content'));

                if (!empty($reply)) {
                    $successfulModel = $model;

                    \Log::info('Groq chatbot succeeded', [
                        'model' => $successfulModel
                    ]);

                    break;
                }

                \Log::warning('Groq returned empty reply', [
                    'model' => $model
                ]);
            } else {

                \Log::error('Groq model failed', [
                    'model'  => $model,
                    'status' => $response->status(),
                    'error'  => $response->json('error.message') ?? $response->body(),
                ]);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            \Log::error('Groq connection error', [
                'model' => $model,
                'error' => $e->getMessage(),
            ]);

        } catch (\Throwable $e) {

            \Log::error('Groq exception', [
                'model' => $model,
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
        }
    }

    if (empty($reply)) {
        \Log::error('All Groq chatbot models failed', [
            'models' => $models
        ]);

        return response()->json([
            'success' => false,
            'reply'   => 'Sorry, I am unable to respond right now. Please try again in a moment.'
        ], 503);
    }

    return response()->json([
        'success' => true,
        'reply'   => $reply,
        'model'   => $successfulModel,
    ]);
}

    private function buildContext(string $message): string
    {
        $context = '';
        $msg     = strtolower($message);
        if (auth()->check()) {
            $user = auth()->user();
            $context .= "Logged in user: {$user->name} (Role: {$user->role})\n";

            if ($user->role === 'customer' && $user->customer) {
                $orders = Order::where('customer_id', $user->customer->id)
                    ->with('tailor.user')
                    ->latest()
                    ->take(5)
                    ->get();

                if ($orders->count() > 0) {
                    $context .= "\nCustomer's Recent Orders:\n";
                    foreach ($orders as $order) {
                        $context .= "- Order #{$order->order_number}: {$order->dress_type}, Status: {$order->status}, Payment: {$order->payment_status}";
                        if ($order->price) {
                            $context .= ", Price: PKR {$order->price}";
                        }
                        if ($order->expected_delivery_date) {
                            $context .= ", Expected Delivery: {$order->expected_delivery_date}";
                        }
                        if ($order->tailor && $order->tailor->user) {
                            $context .= ", Tailor: {$order->tailor->user->name}";
                        }
                        $context .= "\n";
                    }
                } else {
                    $context .= "\nCustomer has no orders yet.\n";
                }
            }

            if ($user->role === 'tailor' && $user->tailor) {
                $tailor        = $user->tailor;
                $pendingOrders = Order::where('tailor_id', $tailor->id)
                    ->where('status', 'pending')
                    ->count();
                $activeOrders  = Order::where('tailor_id', $tailor->id)
                    ->whereIn('status', ['accepted', 'in_progress', 'ready'])
                    ->count();

                $context .= "\nTailor Info: Available slots: {$tailor->available_slots}, Pending orders: {$pendingOrders}, Active orders: {$activeOrders}\n";
            }
        }

        if (str_contains($msg, 'tailor') || str_contains($msg, 'available')) {
            $availableTailors = Tailor::where('available_slots', '>', 0)
                ->where('status', 'approved')
                ->with('user')
                ->take(3)
                ->get();

            if ($availableTailors->count() > 0) {
                $context .= "\nAvailable Tailors:\n";
                foreach ($availableTailors as $t) {
                    $context .= "- {$t->user->name}: {$t->specialization}, {$t->available_slots} slots available\n";
                }
            }
        }

        return $context ?: "No specific database context available.";
    }
}