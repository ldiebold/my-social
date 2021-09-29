<?php

namespace App\Http\Controllers;

use App\Models\RedditAccessToken;
use App\Services\Reddit\Reddit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RedditController extends Controller
{
    public function auth(Reddit $reddit)
    {
        return redirect($reddit->getAuthorizeUrl());
    }

    public function callback(Request $request, Reddit $reddit)
    {
        $token = $reddit->retrieveAccessToken($request->code)
            ->collect();

        RedditAccessToken::create([
            'access_token' => $token['access_token'],
            'token_type' => $token['token_type'],
            'expires_in' => $token['expires_in'],
            'scope' => $token['scope'],
            'refresh_token' => $token['refresh_token'],
            'date_retrieved' => Carbon::now()->getTimestamp()
        ]);

        return 'success';
    }
}
