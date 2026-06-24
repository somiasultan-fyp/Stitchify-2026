<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            $userContext = "Current user: {$user->name}, Role: {$user->role}.";
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
                        'content' => "You are Stitch, the helpful AI assistant for Stitchify — an online tailoring platform in Pakistan. You help customers and tailors with orders, measurements, payments, and delivery. Keep responses short and friendly. {$userContext} Respond in the same language the user writes in.",
                    ],
                    [
                        'role'    => 'user',
                        'content' => $request->message,
                    ],
                ],
                'max_tokens'  => 300,
                'temperature' => 0.7,
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
