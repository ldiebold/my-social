<?php

namespace App\Services\Rebrandly;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class Rebrandly
{
    public const BASE_URL = 'https://api.rebrandly.com/v1/';

    /**
     * Setup transistor for API requests
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function http(): PendingRequest
    {
        return Http::withHeaders([
            'apikey' => env('REBRANDLY_API_KEY')
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
     * @param array $data
     * @return Response
     */
    public function post(string $endpoint, array $data = []): Response
    {
        return $this->http()
            ->post($this->makeUrl($endpoint), $data);
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
}
