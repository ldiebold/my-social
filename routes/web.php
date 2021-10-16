<?php

use App\Http\Controllers\CaddyController;
use App\Http\Controllers\CoverImageController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\RedditController;
use App\Http\Controllers\TwitterController;
use App\Http\Controllers\YoutubeController;
use Illuminate\Support\Facades\Route;

// Caddy (for ssl in dev mode)
Route::get('/caddy-check', [CaddyController::class, 'check']);

Route::middleware('oauth.enabled')->group(function () {
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

  // Facebook
  Route::group(['prefix' => 'facebook'], function () {
    Route::get('auth', [FacebookController::class, 'auth']);
    Route::get('callback', [FacebookController::class, 'callback']);
  });

  // LinkedIn
  Route::group(['prefix' => 'linkedin'], function () {
    Route::get('auth', [LinkedInController::class, 'auth']);
    Route::get('callback', [LinkedInController::class, 'callback']);
  });

  // Twitter
  Route::group(['prefix' => 'twitter'], function () {
    Route::get('auth', [TwitterController::class, 'auth']);
    Route::get('callback', [TwitterController::class, 'callback']);
  });
});
