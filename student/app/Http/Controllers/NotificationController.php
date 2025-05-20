<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = DB::table('notifications')
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->orderBy('created_at', 'desc')
            ->get();

        return $notifications->map(function ($notification) {
            $data = json_decode($notification->data, true);

            return [
                'id' => $notification->id,
                'title' => $data['title'] ?? 'Notification',
                'message' => $data['message'] ?? '',
                'is_read' => !is_null($notification->read_at),
                'created_at' => $notification->created_at,
            ];
        });
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        return response()->json(['status' => 'read']);
    }
}
