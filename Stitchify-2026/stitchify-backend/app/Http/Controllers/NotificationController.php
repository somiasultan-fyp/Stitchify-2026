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

        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    // ── Single notification read mark karo ───────
    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

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

    // ── Latest notifications — AJAX ──────────────
    public function latest()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function($n) {
                return [
                    'id'      => $n->id,
                    'title'   => $n->type ?? 'Notification',
                    'message' => $n->message,
                    'is_read' => $n->is_read,
                    'time'    => $n->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications
        ]);
    }
}