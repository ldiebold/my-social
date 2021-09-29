<?php

namespace App\Services\YouTube;

use App\Models\GoogleAccessToken;
use Carbon\Carbon;
use Google\Client;
use Google\Http\MediaFileUpload;
use Google\Service\YouTube as GoogleYouTube;
use Google\Service\YouTube\Video;
use Google\Service\YouTube\PlaylistItem;
use Google\Service\YouTube\PlaylistItemSnippet;
use Google\Service\YouTube\ResourceId;
use Google\Service\YouTube\VideoSnippet;
use Google\Service\YouTube\VideoStatus;

class YouTube
{
    public Client $client;
    public GoogleYouTube $youtube;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->youtube = new GoogleYouTube($client);
    }

    public function setAccessToken(): self
    {
        $googleAccessToken = GoogleAccessToken::first();
        $this->client->setAccessToken($googleAccessToken->toArray());
        return $this;
    }

    public function refreshTokenIfExpired(): self
    {
        $googleAccessToken = GoogleAccessToken::first();
        if ($this->client->isAccessTokenExpired()) {
            $accessToken = $this->client->fetchAccessTokenWithRefreshToken($googleAccessToken->refresh_token);
            $googleAccessToken->update([
                'access_token' => $accessToken['access_token'],
                'created' => $accessToken['created'],
                'refresh_token' => $accessToken['refresh_token'],
                'scope' => $accessToken['scope'],
                'token_type' => $accessToken['token_type']
            ]);
        }

        return $this;
    }

    public function setupDefaultAccessToken(): self
    {
        $this->setAccessToken()
            ->refreshTokenIfExpired();

        return $this;
    }

    public function makeVideo(
        array $snippetData,
        string|Carbon $privacyStatus = 'private'
    ): Video {
        $this->setupDefaultAccessToken();

        $video = new Video();

        $snippet = new VideoSnippet($snippetData);
        $status = new VideoStatus();

        if (is_a($privacyStatus, Carbon::class)) {
            $status->setPrivacyStatus('private');
            $status->setPublishAt($privacyStatus->toIso8601String());
        } else {
            $status->setPrivacyStatus($privacyStatus);
        }

        $video->setSnippet($snippet);
        $video->setStatus($status);

        return $video;
    }

    public function uploadVideo(
        string $filePath,
        array $data,
        string|Carbon $status = 'private'
    ): Video {
        $chunkSize = 1 * 1024 * 1024;

        $this->client->setDefer(true);

        $video = $this->makeVideo($data, $status);

        /** @var \Psr\Http\Message\RequestInterface */
        $insert = $this->youtube->videos->insert(
            'status,snippet',
            $video
        );

        $media = new MediaFileUpload(
            $this->client,
            $insert,
            'video/*',
            null,
            true,
            $chunkSize
        );

        $media->setFileSize(filesize($filePath));

        $uploadStatus = false;
        $handle = fopen($filePath, "rb");

        while (!$uploadStatus && !feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            $uploadStatus = $media->nextChunk($chunk);
        }

        fclose($handle);

        $this->client->setDefer(false);

        ray($uploadStatus);

        return $uploadStatus;
    }

    public function changeVideoCoverImage(string $imagePath, string $videoId)
    {
        $this->setupDefaultAccessToken();

        $chunkSizeBytes = 1 * 1024 * 1024;

        $this->client->setDefer(true);

        /** @var \Psr\Http\Message\RequestInterface */
        $setRequest = $this->youtube->thumbnails->set($videoId);

        $media = new MediaFileUpload(
            $this->client,
            $setRequest,
            'image/png',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($imagePath));

        $status = false;
        $handle = fopen($imagePath, "rb");

        while (!$status && !feof($handle)) {
            $chunk  = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        fclose($handle);

        $this->client->setDefer(false);

        return $status;
    }

    public function addVideoToPlaylist(string $videoId, string $playlistId)
    {
        $this->setupDefaultAccessToken();

        $resourceId = new ResourceId();
        $resourceId->setVideoId($videoId);
        $resourceId->setKind("youtube#video");

        $playlistItemSnippet = new PlaylistItemSnippet();
        $playlistItemSnippet->setPlaylistId($playlistId);
        $playlistItemSnippet->setResourceId($resourceId);

        $playlistItem = new PlaylistItem();
        $playlistItem->setSnippet($playlistItemSnippet);

        $response = $this->youtube->playlistItems->insert(
            'snippet',
            $playlistItem
        );

        return $response;
    }
}
