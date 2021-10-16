<?php

namespace App\Services\LinkedIn;

use App\Models\LinkedInAccessToken;
use Arr;
use ErrorException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Str;

class LinkedIn
{
    public const API_BASE_URL = 'https://api.linkedin.com/v2/';
    public const AUTH_BASE_URL = 'https://www.linkedin.com/oauth/v2/';

    public string $clientId;
    private string $clientSecret;
    public string $redirectUri;
    public string $userId;
    public array $scope;
    public LinkedInAccessToken $token;

    public function __construct(array $config)
    {
        try {
            $this->clientId = $config['client_id'];
            $this->clientSecret = $config['client_secret'];
            $this->redirectUri = $config['redirect_uri'];
            if (Arr::has($config, 'user_id')) {
                $this->userId = $config['user_id'];
            }
        } catch (\Throwable $th) {
            throw new ErrorException('LinkedIn config requires a client_id, client_secret and redirect_uri');
        }
    }

    public function setToken(LinkedInAccessToken $token)
    {
        $this->token = $token;
    }

    public function usingDefaultAccount(): self
    {
        $this->setToken(LinkedInAccessToken::first());
        $this->userId = config('LINKEDIN_USER_ID');

        return $this;
    }

    public function setScope(array $value)
    {
        $this->scope = $value;
    }

    public function http($baseUrl = self::API_BASE_URL): PendingRequest
    {
        $http = Http::baseUrl($baseUrl)->asForm();

        if (isset($this->token)) {
            return $http->withHeaders([
                'Authorization' => 'Bearer ' . $this->token->access_token
            ]);
        }

        return $http;
    }

    public function getAuthUrl(): string
    {
        $query = Arr::query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => env('APP_URL') . '/linkedin/callback',
            'scope' => implode(" ", $this->scope)
        ]);

        return static::AUTH_BASE_URL . 'authorization/?' . $query;
    }

    public function fetchToken(string $code)
    {
        $data = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
        ];

        return $this->http(static::AUTH_BASE_URL)
            ->asForm()
            ->post('accessToken', $data);
    }

    /**
     * Using the given endpoint, make a full URL
     *
     * @param string $endpoint
     * @return string
     */
    public function makeUrl(string $endpoint): string
    {
        return $this::API_BASE_URL . $endpoint;
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
        return $this->http()->get($endpoint, $params);
    }

    /**
     * Execute a POST request
     *
     * @param string $endpoint
     * @param array $data
     * @return Response
     */
    public function post(string $endpoint, array $data = []): Response
    {
        return $this->http()
            ->post($endpoint, $data);
    }

    /**
     * Execute a PATCH request
     *
     * @param string $endpoint
     * @param array $data
     * @return Response
     */
    public function patch(string $endpoint, array $data = []): Response
    {
        return $this->http()
            ->patch($this->makeUrl($endpoint), $data);
    }

    /**
     * Execute a DELETE request
     *
     * @param string $endpoint
     * @param array $data
     * @return Response
     */
    public function delete(string $endpoint, array $data = []): Response
    {
        return $this->http()
            ->delete($this->makeUrl($endpoint), $data);
    }

    public function publishTextPost(string $text, string $subject = null): Collection
    {
        $data = [
            "distribution" => [
                "linkedInDistributionTarget" => []
            ],
            "owner" => "urn:li:person:$this->userId",
            "text" => [
                "text" => $text
            ]
        ];

        if ($subject) {
            $data['subject'] = $subject;
        }

        return $this->usingDefaultAccount()
            ->post('shares', $data)
            ->collect();
    }

    public function publishLinkPost(
        string $link,
        string $linkTitle,
        string $text,
        string $subject = null,
    ): Collection {
        $data = [
            "content" => [
                "contentEntities" => [
                    [
                        "entityLocation" => $link
                    ]
                ],
                "title" => $linkTitle
            ],
            "distribution" => [
                "linkedInDistributionTarget" => []
            ],
            "owner" => "urn:li:person:$this->userId",
            "text" => [
                "text" => $text
            ]
        ];

        if ($subject) {
            $data['subject'] = $subject;
        }

        return $this->usingDefaultAccount()
            ->post('shares', $data)
            ->collect();
    }
}
