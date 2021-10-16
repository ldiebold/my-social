<?php

return [
    'social' => [
        'facebook.page.image' => \App\SocialPublishers\FacebookPageImagePublisher::class,
        'facebook.page.link' => \App\SocialPublishers\FacebookPageLinkPublisher::class,
        'facebook.page.text' => \App\SocialPublishers\FacebookPagePlainTextPublisher::class,
        'reddit.subreddit.link' => \App\SocialPublishers\RedditSubredditLinkPublisher::class,
        'reddit.subreddit.text' => \App\SocialPublishers\RedditSubredditPublisher::class,
        'twitter.image' => \App\SocialPublishers\TwitterImagePublisher::class,
        'twitter.link' => \App\SocialPublishers\TwitterLinkPublisher::class,
    ]
];
