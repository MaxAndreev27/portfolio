<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Канал для повідомлень
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return Conversation::find($conversationId)->users()->where('user_id', $user->id)->exists();
});

// Канал для статусу "В мережі" (Presence Channel)
Broadcast::channel('online', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
