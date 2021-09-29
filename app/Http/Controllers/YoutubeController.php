<?php

namespace App\Http\Controllers;

use App\Models\GoogleAccessToken;
use Google\Client;
use Google\Service\YouTube;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class YoutubeController extends Controller
{
    public function auth()
    {
        $credentialsPath = storage_path(env('GOOGLE_CREDENTIALS_PATH'));

        $client = new Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope([YouTube::YOUTUBE, YouTube::YOUTUBE_UPLOAD]);
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);

        $redirect_uri = env('APP_URL') . '/youtube/callback';

        $client->setRedirectUri($redirect_uri);

        return redirect($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $credentialsPath = storage_path(env('GOOGLE_CREDENTIALS_PATH'));

        $client = new Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope([YouTube::YOUTUBE, YouTube::YOUTUBE_UPLOAD]);
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);

        $redirect_uri = env('APP_URL') . '/youtube/callback';

        $client->setRedirectUri($redirect_uri);

        if ($request->code) {
            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            Log::info($token);
            GoogleAccessToken::query()->delete();
            GoogleAccessToken::create([
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
                'scope' => $token['scope'],
                'token_type' => $token['token_type'],
                'created' => $token['created'],
            ]);
            return 'credentials stored';
        } else {
            return new BadRequestHttpException('code param is missing');
        }
    }
}
