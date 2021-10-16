<?php

namespace App\Services\Facebook;

use App\Models\FacebookAccessToken;
use Http;
use League\OAuth2\Client\Provider\Facebook as FacebookClient;

class Facebook
{
    public const BASE_URL = 'https://graph.facebook.com/v12.0/';

    public FacebookAccessToken $accessToken;

    public FacebookClient $client;

    public function __construct(array $config)
    {
        $client = new FacebookClient($config);

        $this->client = $client;
    }

    public function makeUrl($resource): string
    {
        return static::BASE_URL . $resource;
    }

    public function getUploadSession(): string
    {
        $uploadSession = Http::post(
            $this->makeUrl('app/uploads'),
            $this->getAuthArray()
        )->throw();

        return $uploadSession['id'];
    }

    // public function uploadPicture(string $picture)
    // {
    //     $uploadSession = $this->getUploadSession();
    //     return $this->post($uploadSession);
    // }

    public function post(string $resource, array $data)
    {
        return Http::post(
            $this->makeUrl($resource),
            array_merge($data, $this->getAuthArray())
        );
    }

    public function get(string $resource, array $query = [])
    {
        ray(static::BASE_URL . $resource);
        return Http::get(
            static::BASE_URL . $resource,
            array_merge($query, $this->getAuthArray())
        );
    }

    public function getAuthArray(): array
    {
        return [
            'access_token' => $this->accessToken->access_token,
            'appsecret_proof' => $this->getAppSecretProof()
        ];
    }

    public function getAppSecretProof(): string
    {
        return hash_hmac(
            'sha256',
            $this->accessToken->access_token,
            env('FACEBOOK_CLIENT_SECRET')
        );
    }

    public function usingDefaultAccessToken(): self
    {
        $this->accessToken = FacebookAccessToken::first();

        return $this;
    }
}
