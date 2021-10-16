<?php

namespace App\Services\Reddit;

use App\Models\RedditAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Str;

class Reddit
{
    public const AUTH_URL = 'https://www.reddit.com/api/v1/';
    public const OAUTH_URL = 'https://oauth.reddit.com/api/';

    private string $clientId;
    protected string $clientSecret;
    public string $redirectUri;

    public RedditAccessToken $token;

    public function __construct(array $config)
    {
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->redirectUri = $config['redirect_uri'];
    }

    /**
     * Setup reddit for API requests
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function http($baseUrl = self::OAUTH_URL): PendingRequest
    {
        $http = Http::baseUrl($baseUrl);

        if (isset($this->token)) {
            $this->ensureTokenFresh($this->token);
            return $http->withHeaders([
                'Authorization' => "bearer " . $this->token->access_token
            ]);
        } else {
            return $http;
        }
    }

    public function retrieveAccessToken(string $code)
    {
        $endpoint = static::AUTH_URL . 'access_token';

        return Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post($endpoint, [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->redirectUri
            ])->throw();
    }

    /**
     * Using the given endpoint, make a full URL
     *
     * @param string $endpoint
     * @return string
     */
    public function makeUrl(string $endpoint): string
    {
        return $this::OAUTH_URL . $endpoint;
    }

    /**
     * Execute a GET request
     *
     * @param string $endpoint
     * @param array $params
     * @return Response
     */
    public function get(string $endpoint, array $params = []): Response
    {
        return $this->http()
            ->get($endpoint, $params);
    }

    public function setToken(RedditAccessToken $token)
    {
        $this->token = $token;
    }

    /**
     * Make an endpoint (base_url not included) this is mostly
     * to make it easy to add an array of query params
     *
     * @param string $endpoint
     * @param array $queryParams
     * @return string
     */
    public function makeEndpoint(string $endpoint, array $queryParams = []): string
    {
        $query = count($queryParams) ? ('?' . Arr::query($queryParams)) : '';
        return $endpoint . $query;
    }

    /**
     * Execute a POST request
     *
     * @param string $endpoint
     * @param array $queryParams
     * @param array $data
     * @return Collection
     */
    public function post(
        string $endpoint,
        array $data = [],
        array $queryParams = [],
        array $attachments = []
    ): Collection {
        $http = $this->http();

        collect($attachments)->each(function ($attachment) use (&$http) {
            $http->attach($attachment[0], $attachment[1], $attachment[2]);
        });

        return $this->http()
            ->asForm()
            ->post(
                $this->makeEndpoint($endpoint, $queryParams),
                $data
            )->throw()
            ->collect();
    }

    /**
     * Execute a PATCH request
     *
     * @param string $endpoint
     * @param array $queryParams
     * @param array $data
     * @return Response
     */
    public function patch(
        string $endpoint,
        array $queryParams = [],
        array $data = []
    ): Response {
        return $this->http()
            ->patch(
                $this->makeEndpoint($endpoint, $queryParams),
                $data
            );
    }

    /**
     * Execute a PUT request
     *
     * @param string $endpoint
     * @param array $queryParams
     * @param array $data
     * @return Response
     */
    public function put(
        string $endpoint,
        array $queryParams = [],
        array $data = []
    ): Response {
        return $this->http()
            ->put(
                $this->makeEndpoint($endpoint, $queryParams),
                $data
            );
    }

    public function getAuthorizeUrl(array $scope = [])
    {
        return (string) Str::of(static::AUTH_URL)
            ->append('authorize?')
            ->append(Arr::query([
                'state' => Str::random(8),
                'client_id' => $this->clientId,
                'response_type' => 'code',
                'redirect_uri' => $this->redirectUri,
                'duration' => 'permanent',
                'scope' => implode(' ', $scope),
            ]));
    }

    public function ensureTokenFresh()
    {
        if ($this->token->isStale()) {
            $this->refreshToken();
        }
    }

    public function refreshToken()
    {
        $endpoint = static::AUTH_URL . 'access_token';

        $token = Http::withBasicAuth(
            $this->clientId,
            $this->clientSecret
        )->asForm()
            ->post($endpoint, [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->token->refresh_token
            ])->throw()
            ->collect();

        $this->token->update([
            "access_token" => $token['access_token'],
            "token_type" => $token['token_type'],
            "expires_in" => $token['expires_in'],
            "scope" => $token['scope'],
        ]);

        return $this->token;
    }

    public function usingDefaultAccount(): self
    {
        $this->setToken(RedditAccessToken::first());

        return $this;
    }

    public function publishTextPost(string $sr, string $title, string $body)
    {
        return $this->usingDefaultAccount()->post(
            'submit',
            [
                'sr' => $sr,
                'title' => $title,
                'text' => $body,
                'kind' => 'self'
            ]
        );
    }

    public function publishLinkPost(
        string $sr,
        string $link,
        string $title,
        string $body
    ): Collection {
        return $this->usingDefaultAccount()->post(
            'submit',
            [
                'sr' => $sr,
                'title' => $title,
                'text' => $body,
                'kind' => 'link',
                'url' => $link
            ]
        );
    }

    public function publishImagePost(
        string $sr,
        string $imageUrl,
        string $title,
        string $body
    ) {
        return $this->usingDefaultAccount()
            ->post(
                'submit',
                [
                    'sr' => $sr,
                    'title' => $title,
                    'text' => $body,
                    'kind' => 'image',
                    'url' => $imageUrl
                ],
                []
            );
    }

    public function publishVideoPost(
        string $sr,
        string $videoUrl,
        string $title,
        string $body
    ) {
        return $this->usingDefaultAccount()
            ->post(
                'submit',
                [
                    'sr' => $sr,
                    'title' => $title,
                    'text' => $body,
                    'kind' => 'video',
                    'video_url' => $videoUrl,
                    'url' => $videoUrl
                ],
                []
            );
    }
}
