<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Str;

class ChatHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'chat_id',
        'from',
        'message'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function getMarkdownMessageAttribute() {
        return Str::markdown($this->message);
    }
}
