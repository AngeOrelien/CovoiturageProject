<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $conversations = Conversation::whereHas('participants', fn($q) =>
                $q->where('user_id', $userId)
            )
            ->with([
                'booking.ride.origin',
                'booking.ride.destination',
                'participants.user',
                'messages' => fn($q) => $q->latest('created_at')->limit(1),
            ])
            ->withCount(['messages as unread_count' => fn($q) =>
                $q->where('is_read', false)->where('sender_id', '!=', $userId)
            ])
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return response()->json($conversations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Conversation $conversation): JsonResponse
    {
        // Marquer les messages comme lus
        $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender:id,first_name,last_name,avatar_url')
            ->orderBy('created_at')
            ->paginate($request->per_page ?? 50);

        return response()->json([
            'conversation' => $conversation->load('participants.user', 'booking.ride'),
            'messages'     => $messages,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
