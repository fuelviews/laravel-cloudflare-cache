<?php

namespace Fuelviews\CloudflareCache;

use Fuelviews\CloudflareCache\Exceptions\CloudflareCacheRequestException;
use Fuelviews\CloudflareCache\Services\CloudflareServiceInterface;

readonly class CloudflareCache implements CloudflareCacheInterface
{
    public function __construct(private CloudflareServiceInterface $cloudflareService)
    {
        //
    }

    /**
     * @param  array<string, array<int, string>|true>  $options
     */
    protected function purge(array $options = []): bool|string
    {
        $response = $this->cloudflareService->post('/purge_cache', $options);

        $responseData = $response->json();

        if (! $response->successful()) {
            throw CloudflareCacheRequestException::requestError($response->status(), $responseData['errors'][0]['message'] ?? '-', $responseData['errors'][0]['code'] ?? null);
        }

        if (! ($responseData['success'] ?? false)) {
            return false;
        }

        return $responseData['result']['id'];
    }

    public function purgeEverything(): bool|string
    {
        return $this->purge([
            'purge_everything' => true,
        ]);
    }

    public function ive(): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        if (! config('cloudflare-cache.api_key')
            || ! config('cloudflare-cache.identifier')
        ) {
            return false;
        }

        if (config('cloudflare-cache.debug')) {
            return true;
        }

        return app()->isProduction();
    }
}
