<?php

namespace App\Http\Controllers;

use App\Models\FacebookAccessToken;
use App\Services\Facebook\Facebook;
use Illuminate\Http\Request;

class FacebookController extends Controller
{
    public function makeFacebookProvider()
    {
        return new Facebook([
            'clientId'          => env('FACEBOOK_CLIENT_ID'),
            'clientSecret'      => env('FACEBOOK_CLIENT_SECRET'),
            'redirectUri'       => env('FACEBOOK_REDIRECT_URI'),
            'graphApiVersion'   => 'v12.0',
        ]);
    }

    public function auth()
    {
        $facebook = $this->makeFacebookProvider();

        return redirect($facebook->client->getAuthorizationUrl([
            'scope' => [
                'publish_to_groups',
                'pages_manage_posts',
                'pages_read_engagement',
                'pages_show_list',
            ]
        ]));
    }

    public function callback(Request $request)
    {
        if ($request->code) {
            $facebook = $this->makeFacebookProvider();
            $token = $facebook->client->getAccessToken(
                'authorization_code',
                ['code' => $request->code]
            );

            $longLivedToken = $facebook->client
                ->getLongLivedAccessToken($token->getToken());

            FacebookAccessToken::query()->delete();

            FacebookAccessToken::create([
                'access_token' => $longLivedToken->getToken(),
                'expires' => $longLivedToken->getExpires(),
                'token_type' => $longLivedToken->getValues()['token_type'],
            ]);

            return 'token stored';
        }

        return abort(402, 'no auth code found');
    }
}
