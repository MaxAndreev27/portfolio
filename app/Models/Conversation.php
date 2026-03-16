<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['name', 'is_group'];

    // Учасники розмови
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // Повідомлення в цій розмові
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    // Останнє повідомлення (для списку чатів)
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
