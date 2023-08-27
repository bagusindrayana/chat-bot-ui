<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SettingHelper;
use App\Models\Chat;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Native\Laravel\Facades\Notification;

class ChatController extends Controller
{


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $chat = Chat::find($id);
        return view('chat-room', compact('chat'));
    }

    public function sendMessage($id, Request $request)
    {   
        try {
            $chat = Chat::find($id);
            if ($chat == null) {
                $chat = Chat::create([
                    'title' => mb_substr($request->message, 0, 20)
                ]);
            }
            $client = new Client();
            $url = SettingHelper::get('api_url') . SettingHelper::get('api_chat');
            $stream = SettingHelper::get('stream_message');
            $token = SettingHelper::get('api_token');
            $model = SettingHelper::get('model');

            $mapHistories = $chat->chat_histories->map(function ($history) {
                return [
                    "role" => $history->from,
                    "content" => $history->message
                ];
            })->toArray();
            $body = [
                "model" => $model ?? "gpt-3.5-turbo",
                "messages" => array_merge($mapHistories, [
                    [
                        "role" => "user",
                        "content" => $request->message
                    ]
                ]),
                "stream" => $stream ? true : false,
            ];
            
            $headers = [
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/json',
            ];
            if ($stream) {
                $chat->chat_histories()->create([
                    'from' => 'user',
                    'message' => $request->message
                ]);
                $body['stream'] = $stream ? true : false;

                $options = [
                    'headers' => $headers,
                    'json' => $body,
                    'stream' => $stream ? true : false,
                ];


                return response()->stream(function () use ($client, $url, $options) {
                    $response = $client->request('POST', $url, $options);
                    $body = $response->getBody();
                    while (!$body->eof()) {
                        $s = Utils::readline($body);
                        echo $s;
                        ob_flush();
                        flush();
                    }
                    // echo "event: update\n";
                    // echo 'data: <END_STREAMING_SSE>';
                    // echo "\n\n";
                    // ob_flush();
                    // flush();
                    
                }, 200, [
                    'Cache-Control' => 'no-cache',
                    'X-Accel-Buffering' => 'no',
                    'Content-Type' => 'text/event-stream',
                    'Chat-Id' => $chat->id
                ]);

            } else {
                $options = [
                    'headers' => $headers,
                    'json' => $body,
                ];
                $response = $client->request('POST', $url, $options);
                $response_body = $response->getBody();
                $response_contents = $response_body->getContents();
                $response_body->close();
                $chat->chat_histories()->create([
                    'from' => 'user',
                    'message' => $request->message
                ]);
                $chat->chat_histories()->create([
                    'from' => 'assistant',
                    'message' => json_decode($response_contents)->choices[0]->message->content
                ]);
                return response($response_contents, $response->getStatusCode())
                    ->header('Content-Type','application/json')
                    ->header('Chat-Id', $chat->id);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th);
            Notification::title('Ops something went wrong!')
            ->message($th->getMessage())
            ->show();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function saveMessage(Chat $chat,Request $request) {
        $chat->chat_histories()->create([
            'from' => 'assistant',
            'message' => $request->message
        ]);
        // Notification::title('New message from AI!')
        //     ->message( mb_substr($request->message, 0, 50))
        //     ->show();
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function chatHistory(Chat $chat) {
        return view('layouts.components.chat-history', compact('chat'));
    }

    public function destroy(Chat $chat) {
        DB::beginTransaction();
        try {
            $chat->chat_histories()->delete();
            $chat->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Chat deleted!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }



}