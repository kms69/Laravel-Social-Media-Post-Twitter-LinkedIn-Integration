<?php


use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\TwitterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('twitter/post', [TwitterController::class, 'postTweet']);
Route::post('linkedin/post', [LinkedinController::class, 'post']);
Route::get('/linkedin/callback', [LinkedInController::class, 'callback']);

