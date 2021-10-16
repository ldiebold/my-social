<?php

namespace App\Http\Controllers;

use App;
use App\Models\TwitterTokenCredentials;
use Illuminate\Http\Request;
use League\OAuth1\Client\Server\Twitter;
use Session;

class TwitterController extends Controller
{
    public function auth(Twitter $twitter)
    {
        $temporaryCredentials = $twitter->getTemporaryCredentials();

        Session::put('twitter_temporary_credentials', $temporaryCredentials);

        return redirect($twitter->getAuthorizationUrl($temporaryCredentials));
    }

    public function callback(Request $request, Twitter $twitter)
    {
        if ($request->has(['oauth_token', 'oauth_verifier'])) {
            $temporaryCredentials = Session::get('twitter_temporary_credentials');

            $tokenCredentials = $twitter->getTokenCredentials(
                $temporaryCredentials,
                $request->oauth_token,
                $request->oauth_verifier
            );

            TwitterTokenCredentials::query()->delete();
            TwitterTokenCredentials::create([
                'identifier' => $tokenCredentials->getIdentifier(),
                'secret' => $tokenCredentials->getSecret()
            ]);

            Session::forget('twitter_temporary_credentials');
            return 'success! Saved the identifier and secret';
        }

        return abort(402, 'no auth code found');
    }
}
