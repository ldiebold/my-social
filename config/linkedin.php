<?php

return [
    'providers' => [
        'default' => [
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
            'redirect_uri' => env('LINKEDIN_REDIRECT_URI', 'https://localhost/linkedin/callback'),
            'user_id' => env('LINKEDIN_USER_ID'),
        ]
    ],
];
