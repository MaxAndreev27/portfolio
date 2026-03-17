<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_ids' => 'required|array',
            'message' => 'required|string',
            'conversation_id' => 'nullable|integer',
        ]);

        $senderId = Auth::id();
        $recipientIds = $request->recipient_ids;
        $allParticipants = array_unique(array_merge([$senderId], $recipientIds));

        // 1. Знаходимо або створюємо розмову
        $conversationId = $request->conversation_id;

        if (!$conversationId) {
            // Шукаємо існуючу розмову з цими ж учасниками (для 1-на-1 або груп)
            $conversation = Conversation::whereHas('users', function ($q) use ($allParticipants) {
                $q->whereIn('user_id', $allParticipants);
            }, '=', count($allParticipants))->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'is_group' => count($recipientIds) > 1,
                    'name' => count($recipientIds) > 1 ? 'Group Chat' : null,
                ]);
                $conversation->users()->attach($allParticipants);
            }
            $conversationId = $conversation->id;
        }

        // 2. Зберігаємо повідомлення
        $message = Message::create([
            'conversation_id' => $conversationId,
            'user_id' => $senderId,
            'body' => $request->message,
        ]);

        // 3. Транслюємо подію через Reverb
        // Використовуємо toOthers(), щоб відправник не отримував дублікат через сокети
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'conversationId' => $conversationId,
            'message' => $message->load('sender')
        ]);
    }

    public function getMessages(Request $request, $userId)
    {
        $senderId = Auth::id();

        // Шукаємо розмову між двома користувачами
        $conversation = Conversation::whereHas('users', function ($q) use ($senderId, $userId) {
            $q->whereIn('user_id', [$senderId, $userId]);
        }, '=', 2)->first();

        if (!$conversation) {
            return response()->json(['messages' => [], 'conversationId' => null]);
        }

        return response()->json([
            'conversationId' => $conversation->id,
            'messages' => $conversation->messages()->with('sender')->latest()->get()->reverse()->values()
        ]);
    }
}
