<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class CaddyController extends Controller
{
    public function check(Request $request)
    {
        ray($request->query('domain'));
        $authorizedDomains = [
            'localhost',
        ];

        if (in_array($request->query('domain'), $authorizedDomains)) {
            return response('Domain Authorized');
        }

        // Abort if there's no 200 response returned above
        abort(503);
    }
}
