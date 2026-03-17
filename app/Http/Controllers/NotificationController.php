<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest('created_at')->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->update(['is_read' => true]);
        return back();
    }

    public function markAllRead()
    {
        Auth::user()->notifications()->update(['is_read' => true]);
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }
}
