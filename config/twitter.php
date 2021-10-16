<?php

return [
    'providers' => [
        'default' => [
            'consumer_key' => env('TWITTER_OAUTH_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_OAUTH_CONSUMER_SECRET'),
            'callback_url' => env('TWITTER_CALLBACK_URL', 'https://localhost/linkedin/callback'),
        ]
    ],
];
