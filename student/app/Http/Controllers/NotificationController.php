<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', 'App\\Models\\User')
            ->latest()
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        if ($notification->notifiable_id === Auth::id()) {
            $notification->is_read = true;
            $notification->save();
        }

        return response()->json(['status' => 'success']);
    }
}