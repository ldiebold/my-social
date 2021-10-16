<?php

namespace App\Services\Facebook;

use League\OAuth2\Client\Provider\Facebook as FacebookClient;
use App\Models\FacebookPage as Page;
use Http;

class FacebookPage
{
    public const BASE_URL = 'https://graph.facebook.com/v12.0/';

    public Page $page;

    public FacebookClient $client;

    public function __construct(array $config)
    {
        $client = new FacebookClient($config);

        $this->client = $client;
    }

    public function usingDefaultPage(): self
    {
        $this->page = Page::first();

        return $this;
    }

    public function uploadPicturePost(string $image, string $body, array $data = [])
    {
        $pageId = $this->page->facebook_id;

        return $this->post(
            "$pageId/photos",
            array_merge(['name' => $body], $data),
            [['attachment', $image, 'photo.jpg']]
        );
    }

    public function publishTextPost(string $body)
    {
        $pageId = $this->page->facebook_id;

        return $this->post(
            "$pageId/feed",
            ['message' => $body],
        );
    }

    public function publishLinkPost(
        string $link,
        string $body,
        array $data = []
    ) {
        $pageId = $this->page->facebook_id;

        return $this->post(
            "$pageId/feed",
            array_merge([
                'message' => $body,
                'link' => $link
            ], $data),
        );
    }

    public function publishVideoPost(
        string $videoLink,
        string $body,
        array $data = []
    ) {
        $pageId = $this->page->facebook_id;

        return $this->post(
            "$pageId/feed",
            array_merge([
                'message' => $body,
                'link' => $videoLink
            ], $data),
        );
    }

    public function post(
        string $resource,
        array $data,
        array $attachments = []
    ) {
        $http = Http::baseUrl(static::BASE_URL);

        collect($attachments)->each(function ($attachment) use (&$http) {
            $http->attach($attachment[0], $attachment[1], $attachment[2]);
        });

        return $http->post(
            $resource,
            array_merge($data, $this->getAuthArray())
        );
    }

    public function get(string $resource, array $query = [])
    {
        $http = Http::baseUrl(static::BASE_URL);

        return $http->get(
            $resource,
            array_merge($query, $this->getAuthArray())
        );
    }

    /**
     * Get an array of values used to authenticate the request.
     *
     * @return array
     */
    public function getAuthArray(): array
    {
        return [
            'access_token' => $this->page->access_token,
            'appsecret_proof' => $this->getAppSecretProof()
        ];
    }

    /**
     * The app secret proof is an optional, extra level of 
     * security offered by facebooks api.
     *
     * @return string
     */
    public function getAppSecretProof(): string
    {
        return hash_hmac(
            'sha256',
            $this->page->access_token,
            env('FACEBOOK_CLIENT_SECRET')
        );
    }

    /**
     * Before uploading a file, we need an "upload session"
     *
     * @param integer $fileLength
     * @param string $fileType e.g. image/jpeg, video/mp4
     * @param string $fileName
     * @return string
     */
    public function getUploadSession(int $fileLength, string $fileType, string $fileName): string
    {
        $http = Http::baseUrl(static::BASE_URL);

        $data = [
            'file_length' => $fileLength,
            'file_type' => $fileType,
            'file_name' => $fileName,
        ];

        $response = $http->post(
            'app/uploads',
            array_merge($data, $this->getAuthArray())
        )->throw()->collect();

        return $response['id'];
    }
}
