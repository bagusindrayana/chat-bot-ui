<?php

namespace App\Http\Helpers;

use App\Models\Chat;
use App\Models\Setting;

class ChatHelper {
    public static function get() {
        return Chat::orderBy('created_at','desc')->get();
    }
}