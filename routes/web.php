<?php

use App\Http\Controllers\CoverImageController;
use App\Http\Controllers\RedditController;
use App\Http\Controllers\YoutubeController;
use Illuminate\Support\Facades\Route;

Route::get('/templates/cover-image', [CoverImageController::class, 'standard']);

// Youtube
Route::group(['prefix' => 'youtube'], function () {
  Route::get('auth', [YoutubeController::class, 'auth']);
  Route::get('callback', [YoutubeController::class, 'callback']);
});

// Reddit
Route::group(['prefix' => 'reddit'], function () {
  Route::get('auth', [RedditController::class, 'auth']);
  Route::get('callback', [RedditController::class, 'callback']);
});
