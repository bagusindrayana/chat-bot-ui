<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
            [
                "key" => "api_url",
                "value" => "https://api.openai.com/v1",
            ],
            [
                "key" => "api_token",
                "value" => "",
            ],
            [
                "key" => "stream_message",
                "value" => false,
            ],
            [
                "key" => "api_chat",
                "value" => "/chat/completions"
            ],
            [
                "key" => "model",
                "value" => "gpt-3.5-turbo"
            ]
        ]);
    }
}
