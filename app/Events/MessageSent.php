<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Використовуємо Now для тестування без черг
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Створюємо екземпляр події.
     * Передаємо модель повідомлення.
     */
    public function __construct(public Message $message)
    {
        // Включаємо відносини, щоб у Vue відразу було ім'я відправника
        $this->message->load('sender');
    }

    /**
     * Визначаємо канали, на які буде транслюватися подія.
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.' . $this->message->conversation_id),
        ];

        // Додаємо індивідуальні канали для кожного отримувача
        // Це дозволить їм отримати сповіщення, навіть якщо чат не відкритий
        $participants = $this->message->conversation->users()->where('user_id', '!=', $this->message->user_id)->get();

        foreach ($participants as $participant) {
            $channels[] = new PrivateChannel('App.Models.User.' . $participant->id);
        }

        return $channels;
    }

    /**
     * Дані, які будуть передані у Vue.
     * Якщо цей метод не визначити, Laravel відправить усі public властивості.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'conversation_id' => $this->message->conversation_id,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->name,
            ],
        ];
    }

    /**
     * Ім'я події у фронтенді (за замовчуванням це ім'я класу).
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
