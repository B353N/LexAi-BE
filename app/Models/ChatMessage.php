<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['chat_session_id', 'sender', 'message'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }
}
