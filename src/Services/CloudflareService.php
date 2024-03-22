<?php

namespace Fuelviews\CloudflareCache\Services;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

readonly class CloudflareService implements CloudflareServiceInterface
{
    public function __construct(
        private Factory $client,
        private ?string $apiKey,
        private ?string $identifier,
    ) {
        //
    }

    public function request(): PendingRequest
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->client->withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
        ]);
    }

    public function getBaseUrl(string $endpoint): string
    {
        return 'https://api.cloudflare.com/client/v4/zones/'.$this->identifier.'/'.ltrim($endpoint, '/');
    }

    /**
     * @param  string[]|array<string, bool>|string[][]  $data
     */
    public function post(string $endpoint, array $data = []): Response
    {
        return $this->request()->post($this->getBaseUrl($endpoint), $data);
    }
}
