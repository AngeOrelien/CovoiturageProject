<?php

namespace App\Http\Controllers;

use App\Models\{Conversation, ConversationParticipant, Message, Booking};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $conversations = Conversation::whereHas('participants', fn($q) => $q->where('user_id', $userId))
            ->with(['booking.passenger', 'booking.ride.driver', 'messages' => fn($q) => $q->latest('created_at')->limit(1)])
            ->latest('created_at')
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $userId = Auth::id();
        abort_unless($conversation->participants()->where('user_id', $userId)->exists(), 403);

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();
        $conversation->messages()->where('sender_id', '!=', $userId)->update(['is_read' => true]);

        return view('conversations.show', compact('conversation', 'messages'));
    }

    public function store(Request $request, Booking $booking)
    {
        $userId = Auth::id();
        abort_if($booking->passenger_id !== $userId && $booking->ride->driver_id !== $userId, 403);

        $conversation = $booking->conversation ?? Conversation::create([
            'ride_id'    => $booking->ride_id,
            'booking_id' => $booking->id,
        ]);

        // Add both participants if new
        foreach ([$booking->passenger_id, $booking->ride->driver_id] as $pid) {
            $conversation->participants()->firstOrCreate(['user_id' => $pid], ['joined_at' => now()]);
        }

        $data = $request->validate(['content' => 'required|string|max:1000']);
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $userId,
            'content'         => $data['content'],
        ]);

        return redirect()->route('conversations.show', $conversation);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $userId = Auth::id();
        abort_unless($conversation->participants()->where('user_id', $userId)->exists(), 403);

        $data = $request->validate(['content' => 'required|string|max:1000']);
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $userId,
            'content'         => $data['content'],
        ]);

        return back();
    }
}
