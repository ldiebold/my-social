<?php

namespace App\Services\Transistor;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Transistor
{
    public const BASE_URL = 'https://api.transistor.fm/v1/';

    /**
     * Setup transistor for API requests
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function http(): PendingRequest
    {
        return Http::withHeaders([
            'x-api-key' => env('TRANSISTOR_API_KEY')
        ]);
    }

    /**
     * Using the given endpoint, make a full URL
     *
     * @param string $endpoint
     * @return string
     */
    public function makeUrl(string $endpoint): string
    {
        return $this::BASE_URL . $endpoint;
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
            $this::BASE_URL . $endpoint,
            $params
        );
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
        array $queryParams = [],
        array $data = []
    ): Response {
        return $this->http()
            ->post(
                $this->makeUrl($endpoint) . '?' . Arr::query($queryParams),
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

    /**
     * Authorize for a file upload. The response returns the "upload_url".
     * We use this "upload_url" to PUT the podcast
     *
     * @param string $filename
     * @return Response
     */
    public function authorizeUpload(string $filename): Response
    {
        return $this->http()
            ->get($this->makeUrl("episodes/authorize_upload"), [
                'filename' => $filename
            ])->throw();
    }

    /**
     * Upload the provided audio file. This is usually sent to the
     * "upload_url" retrieved using the "authorizeUpload" method
     *
     * @param string $file
     * @param string $filename
     * @param string $contentType
     * @param string $uploadUrl
     * @return Response
     */
    public function uploadAudio(
        string $file,
        string $filename,
        string $contentType,
        string $uploadUrl
    ): Response {
        return $this->http()
            ->attach($filename, $file, $filename)
            ->withHeaders(['Content-Type' => $contentType])
            ->put($uploadUrl);
    }

    /**
     * This method makes three requests. First, it gets a URL for audio uploads.
     * Second, we upload the audio file with the provided URL.
     * Third, we create a podcast episode with the newly uploaded audio file.
     *
     * @param string $file
     * @param string $filename
     * @param string $showId
     * @param string $episodeTitle
     * @return Response
     */
    public function createEpisodeWithAudio(
        string $file,
        string $filename,
        string $showId,
        string $episodeTitle,
        array $episodeData = []
    ): Response {
        $authorizeUploadResponse = $this->authorizeUpload($filename)->throw();

        [
            'audio_url' => $audioUrl,
            'upload_url' => $uploadUrl,
            'content_type' => $contentType
        ] = $authorizeUploadResponse->collect()['data']['attributes'];

        $this->uploadAudio($file, $filename, $contentType, $uploadUrl)
            ->throw();

        $response = $this->post('episodes', array_merge([
            'episode[show_id]' => $showId,
            'episode[audio_url]' => $audioUrl,
            'episode[title]' => $episodeTitle
        ], $episodeData));

        return $response;
    }

    /**
     * Get the latest known episode date. Note that any
     * dates where "published_at" is null are ignored
     *
     * @param string $showId
     * @return Carbon
     */
    public function getLatestKnownEpisodeDate(string $showId): Carbon
    {
        $latestEpisodes = $this->get('episodes')
            ->collect()
            ->get('data');

        $latestEpisodeWithPublishDateKey = collect($latestEpisodes)
            ->search(function ($value) use ($showId) {
                return $value['attributes']['published_at'] &&
                    $value['relationships']['show']['data']['id'] === $showId;
            });

        if ($latestEpisodeWithPublishDateKey === false) {
            throw new NotFoundHttpException('Unable to find latest episode. Make sure there is a recent episode with a publish date');
        }

        $latestEpisode = $latestEpisodes[$latestEpisodeWithPublishDateKey];

        return Carbon::make($latestEpisode['attributes']['published_at']);
    }

    public function episodeHasBeenPublished(int $episodeNumber, string $showId)
    {
        $latestEpisodes = $this->get('episodes')
            ->collect()
            ->get('data');

        $latestEpisodeWithPublishDateKey = collect($latestEpisodes)
            ->search(function ($value) use ($showId) {
                return $value['attributes']['published_at'] &&
                    $value['relationships']['show']['data']['id'] === $showId;
            });

        if ($latestEpisodeWithPublishDateKey === false) {
            throw new NotFoundHttpException('Unable to find latest episode. Make sure there is a recent episode with a publish date');
        }

        $latestEpisode = $latestEpisodes[$latestEpisodeWithPublishDateKey];

        return $episodeNumber <= $latestEpisode['attributes']['number'];
    }

    public function latestEpisodeNumber(string $showId)
    {
        $latestEpisodes = $this->get('episodes')
            ->collect()
            ->get('data');

        $latestEpisodeWithPublishDateKey = collect($latestEpisodes)
            ->search(function ($value) use ($showId) {
                return $value['attributes']['published_at'] &&
                    $value['relationships']['show']['data']['id'] === $showId;
            });

        if ($latestEpisodeWithPublishDateKey === false) {
            throw new NotFoundHttpException('Unable to find latest episode. Make sure there is a recent episode with a publish date');
        }

        $latestEpisode = $latestEpisodes[$latestEpisodeWithPublishDateKey];

        return $latestEpisode['attributes']['number'];
    }
}
