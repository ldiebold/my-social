<?php

return [
    'providers' => [
        'default' => [
            'client_id' => env('REDDIT_CLIENT_ID'),
            'client_secret' => env('REDDIT_CLIENT_SECRET'),
            'redirect_uri' => env('REDDIT_REDIRECT_URI', 'https://localhost/reddit/callback'),
        ]
    ],
];
