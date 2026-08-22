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

        $message = $request->message;
        $apiKey  = env('GEMINI_API_KEY');

        // ===== DATABASE CONTEXT =====
        $context = $this->buildContext($message);

        // ===== GEMINI PROMPT =====
        $prompt = "You are Stitch, the helpful assistant for Stitchify — an online tailoring platform in Pakistan.

PLATFORM INFO:
- Customers can browse tailors, place orders, track status, and pay online
- Tailors accept/reject orders, set price and delivery date
- Order statuses: pending → accepted → in_progress → ready → dispatched → delivered

CURRENT DATA FROM DATABASE:
{$context}

USER MESSAGE: {$message}

Instructions:
- Answer based on database data when available
- Keep responses short, friendly, helpful
- If user asks about their order, use the data above
- Respond in same language as user (English/Urdu/Roman Urdu)
- If data not available, give general helpful answer";

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 300,
                        'temperature'     => 0.7,
                    ]
                ]
            );

            $reply = $response->json('candidates.0.content.parts.0.text')
                     ?? 'Sorry, I could not process your request. Please try again.';

        } catch (\Exception $e) {
            $reply = 'Sorry, I am unable to respond right now. Please try again later.';
        }

        return response()->json([
            'success' => true,
            'reply'   => $reply,
        ]);
    }

    // ===== DATABASE CONTEXT BUILDER =====
    private function buildContext(string $message): string
    {
        $context = '';
        $msg     = strtolower($message);

        // Logged in user ka context
        if (auth()->check()) {
            $user = auth()->user();
            $context .= "Logged in user: {$user->name} (Role: {$user->role})\n";

            // Customer ka orders
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

            // Tailor ka context
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

        // General tailor stats (agar message mein tailor mention ho)
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