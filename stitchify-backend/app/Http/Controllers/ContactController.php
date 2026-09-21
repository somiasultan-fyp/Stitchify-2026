<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string|max:50',
            'message' => 'required|string|max:2000',
        ]);

        Complaint::create([
            'user_id'        => auth()->check() ? auth()->id() : null,
            'subject'        => $request->subject . ' — ' . $request->name . ' (' . $request->email . ', ' . $request->phone . ')',
            'message'        => $request->message,
            'status'         => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for contacting us. We will get back to you soon.',
        ]);
    }
}