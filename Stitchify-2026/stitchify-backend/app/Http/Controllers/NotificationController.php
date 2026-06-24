<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ── Sab notifications ────────────────────────
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        // Page khulte hi sab read mark ho jaayein
        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    // ── Single notification read mark karo ───────
    public function markRead(Notification $notification)
    {
        // Sirf apni notification read kar sako
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        // Agar action URL hai toh wahan redirect karo
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back();
    }

    // ── Sab read mark karo ───────────────────────
    public function markAllRead()
    {
        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Sab notifications read ho gayi.');
    }

    // ── Unread count — AJAX ──────────────────────
    public function unreadCount()
    {
        $count = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}