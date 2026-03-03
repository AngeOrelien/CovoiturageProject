<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Conversation;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $request->user()->id,
            'content'         => $data['content'],
            'is_read'         => false,
        ]);

        return response()->json([
            'message' => $message->load('sender:id,first_name,last_name,avatar_url'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Message $message)
    {
        if ($message->sender_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($message->created_at->diffInMinutes(now()) > 10) {
            return response()->json(['message' => 'Impossible de supprimer ce message après 10 minutes.'], 422);
        }

        $message->delete();

        return response()->json(['message' => 'Message supprimé.']);
    }

    /**
     * Marquer un message comme lu.
     */
    public function markRead(Request $request, Message $message): JsonResponse
    {
        // Seul le destinataire peut marquer comme lu
        if ($message->sender_id === $request->user()->id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $message->update(['is_read' => true]);

        return response()->json(['message' => 'Message marqué comme lu.']);
    }

    /**
     * Marquer toute une conversation comme lue.
     */
    public function markAllRead(Request $request, Conversation $conversation): JsonResponse
    {
        $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'Tous les messages marqués comme lus.']);
    }
}
