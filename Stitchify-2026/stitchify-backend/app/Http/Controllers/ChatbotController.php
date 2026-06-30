<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tailor;

class ChatbotController extends Controller
{
    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $apiKey = env('GROQ_API_KEY');

        $userContext = '';

        if (auth()->check()) {
            $user = auth()->user();
            $userContext = "Current logged-in user: {$user->name}, Role: {$user->role}.\n";

            if ($user->role === 'customer' && $user->customer) {
                $orders = $user->customer->orders()
                    ->with('tailor.user')
                    ->latest()
                    ->take(5)
                    ->get();

                if ($orders->count() > 0) {
                    $userContext .= "Here are this customer's recent orders (use this data to answer order status questions, do not make up any other order):\n";

                    foreach ($orders as $order) {
                        $tailorName = $order->tailor->user->name ?? 'N/A';
                        $expected   = $order->expected_delivery_date
                            ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('d M Y')
                            : 'Not set yet';

                        $userContext .= "- Order #{$order->order_number}: {$order->dress_type}, "
                            . "Status: {$order->status}, "
                            . "Tailor: {$tailorName}, "
                            . "Payment: {$order->payment_status}, "
                            . "Expected Delivery: {$expected}\n";
                    }
                } else {
                    $userContext .= "This customer has no orders yet.\n";
                }
            }
        }

        $tailorsContext = '';

        $tailors = Tailor::where('available_slots', '>', 0)
            ->with('user')
            ->take(10)
            ->get();

        if ($tailors->count() > 0) {
            $tailorsContext = "Here is the current list of available tailors on Stitchify (use this real data only, do not invent tailors):\n";

            foreach ($tailors as $tailor) {
                $name  = $tailor->user->name ?? 'N/A';
                $spec  = $tailor->specialization ?? 'General Tailoring';
                $city  = $tailor->city ?? 'Not specified';
                $price = $tailor->base_price ? "Rs. " . number_format($tailor->base_price) : 'Not specified';

                $tailorsContext .= "- {$name}: Specialization: {$spec}, City: {$city}, "
                    . "Starting Price: {$price}, Available Slots: {$tailor->available_slots}\n";
            }
        } else {
            $tailorsContext = "No tailors are currently available with open slots.\n";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'    => 'llama-3.1-8b-instant',
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => "You are Stitch, the official AI assistant for Stitchify — an online tailoring platform in Pakistan. Follow these rules strictly at all times:

1. LANGUAGE: Always reply in the exact same language the user writes in. If the user writes in English, reply only in English. If the user writes in Roman Urdu, reply only in Roman Urdu. If the user writes in Urdu script, reply only in Urdu script. Never mix languages, and never switch language on your own.

2. TONE: Keep a professional, neutral, and respectful tone at all times. Do not use casual terms of address such as 'bhai', 'sister', 'baji', 'dost', 'yaar', or any similar informal labels. Do not be overly friendly, casual, or chatty. Do not use excessive exclamation marks or emojis. Speak plainly and directly, like a professional customer support assistant.

3. SCOPE: Only answer questions related to Stitchify and tailoring — placing orders, measurements, payments, delivery, finding tailors, order status, and general tailoring advice. If asked something unrelated to Stitchify or tailoring, politely state that you can only help with Stitchify-related questions.

4. ACCURACY: Only use the real data provided below to answer questions about orders or tailors. Never assume or invent order details, tailor names, prices, or any information not explicitly given to you. If the requested information is not present in the data below, tell the user to log in to their account or contact support at stitchify.biz.

5. LENGTH: Keep responses short, clear, and to the point. Avoid unnecessary elaboration.

6. IDENTITY: Do not break character. You are Stitch, Stitchify's assistant, not a general-purpose AI.

{$userContext}

{$tailorsContext}",
                    ],
                    [
                        'role'    => 'user',
                        'content' => $request->message,
                    ],
                ],
                'max_tokens'  => 300,
                'temperature' => 0.5,
            ]);

            $reply = $response->json('choices.0.message.content')
                     ?? 'Sorry, I could not process your request.';

            return response()->json([
                'success' => true,
                'reply'   => $reply,
            ]);

        } catch (\Exception $e) {
            \Log::error('Groq Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'reply'   => 'Sorry, I am unable to respond right now.',
            ], 500);
        }
    }
}