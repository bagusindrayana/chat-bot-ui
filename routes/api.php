<?php

use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('chat/{chat}/send-message', [ChatController::class,'sendMessage'])->name('api.chat.send-message');
Route::post('chat/{chat}/save-message', [ChatController::class,'saveMessage'])->name('api.chat.save-message');
Route::get('chat/{chat}/history', [ChatController::class,'chatHistory'])->name('chat.history');