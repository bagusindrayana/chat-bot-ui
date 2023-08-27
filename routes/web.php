<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('chat/{chat}', [ChatController::class,'show'])->name('chat.show');
Route::post('setting', [SettingController::class,'update'])->name('setting.update');
Route::delete('chat/{chat}/destroy', [ChatController::class,'destroy'])->name('chat.destroy');
