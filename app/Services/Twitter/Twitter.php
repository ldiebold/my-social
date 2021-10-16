<?php

namespace App\Services\Twitter;

use App\Models\TwitterTokenCredentials;
use Arr;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Twitter
{
    const OAUTH_URL = 'https://api.twitter.com/1.1/';

    public string $consumerKey;
    private string $consumerSecret;

    public string $token;
    private string $tokenSecret;

    public string $callbackUrl;

    public function __construct($config = [])
    {
        $this->consumerKey = $config['consumer_key'];
        $this->consumerSecret = $config['consumer_secret'];
        $this->callbackUrl = $config['callback_url'];

        if (Arr::has($config, 'token')) {
            $this->token = $config['token'];
        }

        if (Arr::has($config, 'token_secret')) {
            $this->tokenSecret = $config['token_secret'];
        }
    }

    /**
     * Setup Twitter for API requests
     *
     * @return GuzzleClient
     */
    public function http($baseUrl = self::OAUTH_URL): GuzzleClient
    {
        $guzzleConfig = ['base_uri' => $baseUrl];

        if (isset($this->token) && isset($this->tokenSecret)) {
            $stack = HandlerStack::create();
            $middleware = new Oauth1([
                'consumer_key'    => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
                'token'           => $this->token,
                'token_secret'    => $this->tokenSecret
            ]);
            $stack->push($middleware);
            $guzzleConfig['handler'] = $stack;
        }

        return new GuzzleClient($guzzleConfig);
    }

    public function usingDefaultToken(): self
    {
        $tokenCredentials = TwitterTokenCredentials::first();

        $this->token = $tokenCredentials->identifier;
        $this->tokenSecret = $tokenCredentials->secret;

        return $this;
    }

    public function post(
        string $endpoint,
        array $data = [],
    ): ResponseInterface {
        $http = $this->http();

        return $http->request(
            'POST',
            $endpoint,
            [
                'auth' => 'oauth',
                'form_params' => $data
            ]
        );
    }

    public function uploadImage(string $image): Collection
    {
        $http = $this->http('https://upload.twitter.com/1.1/');

        $response = $http->request('POST', 'media/upload.json', [
            'auth' => 'oauth',
            'multipart' => [
                [
                    'name'     => 'media',
                    'contents' => $image,
                ]
            ]
        ]);

        return $this->collectResponse($response);
    }

    public function collectResponse(ResponseInterface $response): Collection
    {
        return new Collection(
            json_decode($response->getBody()->getContents(), true)
        );
    }

    public function updateStatusWithImage(
        string $status,
        string $image
    ) {
        $imageResponse = $this->uploadImage($image);

        $data = [
            'status' => $status,
            'media_ids' => $imageResponse['media_id']
        ];

        $response = $this->post('statuses/update.json', $data);

        return $this->collectResponse($response);
    }

    public function updateStatus(string $status)
    {
        $data = ['status' => $status,];

        $response = $this->post('statuses/update.json', $data);

        return $this->collectResponse($response);
    }
}
