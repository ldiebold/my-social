<?php

namespace App\Http\Controllers;

use App;
use App\Models\LinkedInAccessToken;
use App\Services\LinkedIn\LinkedIn;
use Illuminate\Http\Request;

class LinkedInController extends Controller
{
    public function makeLinkedInProvider(): LinkedIn
    {
        $linkedin = App::make(LinkedIn::class);
        $linkedin->setScope([
            'r_liteprofile',
            'r_emailaddress',
            'w_member_social'
        ]);

        return $linkedin;
    }

    public function auth()
    {
        $linkedin = $this->makeLinkedInProvider();

        return redirect($linkedin->getAuthUrl());
    }

    public function callback(Request $request)
    {
        if ($request->code) {
            $token = $this->makeLinkedInProvider()
                ->fetchToken($request->code);

            LinkedInAccessToken::query()->delete();
            $token = LinkedInAccessToken::create([
                'access_token' => $token['access_token'],
                'expires_in' => $token['expires_in'],
                'created' => now()->timestamp
            ]);

            // $token->expi

            return 'Success! Token has been stored';
        }

        return abort(402, 'no auth code found');
    }
}
