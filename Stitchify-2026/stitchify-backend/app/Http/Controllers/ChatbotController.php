<?php
// app/Http/Controllers/ChatbotController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
{
    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // Agar user logged in hai toh unki info bhi do chatbot ko
        $userContext = '';
        if (auth()->check()) {
            $user = auth()->user();
            $userContext = "Current user: {$user->name}, Role: {$user->role}.";
        }

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    // System prompt — chatbot ko batao ke woh kya hai
                    [
                        'role'    => 'system',
                        'content' => "You are Stitch, the helpful AI assistant 
                            for Stitchify — an online tailoring platform in Pakistan.
                            You help customers and tailors with queries about:
                            - How to place orders
                            - How to take measurements
                            - Order status questions
                            - Pricing and payment queries
                            - How to register as a tailor
                            - General tailoring advice
                            {$userContext}
                            Keep responses short, friendly and helpful.
                            If asked something unrelated to tailoring or Stitchify,
                            politely redirect to relevant topics.
                            Respond in the same language the user writes in
                            (English, Urdu, or Roman Urdu).",
                    ],
                    // User ka message
                    [
                        'role'    => 'user',
                        'content' => $request->message,
                    ],
                ],
                'max_tokens'  => 300,
                'temperature' => 0.7,
            ]);

            $reply = $response->choices[0]->message->content;

            return response()->json([
                'success' => true,
                'reply'   => $reply,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'reply'   => 'Sorry, I am unable to respond right now. Please try again later.',
            ], 500);
        }
    }
}