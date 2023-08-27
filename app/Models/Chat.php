<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function chat_histories()
    {
        return $this->hasMany(ChatHistory::class);
    }
}
