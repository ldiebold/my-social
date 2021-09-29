<?php

namespace App\Services\Reddit;

use App\Models\RedditAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Str;

class Reddit
{
    public const AUTH_URL = 'https://www.reddit.com/api/v1/';
    public const OAUTH_URL = 'https://oauth.reddit.com/api/';

    private string $clientId;
    private string $clientSecret;
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
    public function http(): PendingRequest
    {
        if ($this->token) {
            $this->ensureTokenFresh($this->token);
        }

        ray("bearer " . $this->token->access_token);

        return Http::withHeaders([
            'Authorization' => "bearer " . $this->token->access_token
        ]);
    }

    public function retrieveAccessToken(string $code)
    {
        $endpoint = static::AUTH_URL . 'access_token';

        return Http::post($endpoint, [
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
        return $this->http()->get(
            $this::OAUTH_URL . $endpoint,
            $params
        );
    }

    public function setToken(RedditAccessToken $token)
    {
        $this->token = $token;
    }

    /**
     * Execute a POST request
     *
     * @param string $endpoint
     * @param array $queryParams
     * @param array $data
     * @return Response
     */
    public function post(
        string $endpoint,
        array $data = [],
        array $queryParams = [],
    ): Response {
        $query = count($queryParams) ? ('?' . Arr::query($queryParams)) : '';

        ray($this->makeUrl($endpoint) . $query);

        return $this->http()
            ->asForm()
            ->post(
                $this->makeUrl($endpoint) . $query,
                $data
            );
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
                $this->makeUrl($endpoint) . '?' . Arr::query($queryParams),
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
                $this->makeUrl($endpoint) . '?' . Arr::query($queryParams),
                $data
            );
    }

    public function getAuthorizeUrl()
    {
        return (string) Str::of(static::AUTH_URL)
            ->append('authorize?')
            ->append(Arr::query([
                'state' => Str::random(8),
                'client_id' => $this->clientId,
                'response_type' => 'code',
                'redirect_uri' => $this->redirectUri,
                'duration' => 'permanent',
                'scope' => 'submit save read mysubreddits modposts edit identity'
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
}
