<?php

namespace App\Http\Helpers;

use App\Models\Setting;

class SettingHelper {
    public static function get($key) {
        $setting = Setting::where('key',$key)->first();
        return $setting->value ?? null;
    }
}