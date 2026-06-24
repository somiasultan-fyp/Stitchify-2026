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

        $apiKey = env('GEMINI_API_KEY');

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "You are Stitch, the helpful AI assistant for Stitchify — an online tailoring platform in Pakistan. Help users with orders, measurements, payments, tailors, and delivery. Keep responses short and friendly. User says: " . $request->message
                            ]
                        ]
                    ]
                ]
            ]
        );

        $reply = $response->json('candidates.0.content.parts.0.text') 
                 ?? 'Sorry, I could not process your request. Please try again.';

        return response()->json([
            'success' => true,
            'reply'   => $reply,
        ]);
    }
}