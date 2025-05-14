<?php

use Fuelviews\CloudflareCache\CloudflareCacheInterface;
use Fuelviews\CloudflareCache\Exceptions\CloudflareCacheRequestException;
use Fuelviews\CloudflareCache\Services\CloudflareServiceInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

test('it posts to Cloudflare API correctly', function (): void {
    Http::fake();

    $cloudflareService = app()->make(CloudflareServiceInterface::class);
    $cloudflareService->post('purge_cache');

    Http::assertSentCount(1);
    Http::assertSent(function (Request $request, Response $response): \Illuminate\Http\Client\Request {
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertTrue($request->hasHeader('Content-Type'));
        $this->assertSame($request->url(), 'https://api.cloudflare.com/client/v4/zones//purge_cache');

        expect($response)
            ->status()
            ->toBe(200);

        return $request;
    });
});

test('it constructs correct base URL', function (): void {
    $mockFactory = Mockery::mock(\Illuminate\Http\Client\Factory::class);
    $mockFactory->shouldReceive('withHeaders')->andReturn($mockFactory);
    
    $service = new \Fuelviews\CloudflareCache\Services\CloudflareService(
        $mockFactory,
        'test-api-key',
        'test-zone-id'
    );
    
    $baseUrl = $service->getBaseUrl('purge_cache');
    expect($baseUrl)->toBe('https://api.cloudflare.com/client/v4/zones/test-zone-id/purge_cache');
});

test('purgeEverything sends correct payload', function (): void {
    Http::fake([
        'https://api.cloudflare.com/client/v4/zones/*' => Http::response([
            'success' => true,
            'result' => ['id' => 'request-id-123'],
        ], 200),
    ]);

    $cloudflareCache = app()->make(CloudflareCacheInterface::class);
    $result = $cloudflareCache->purgeEverything();

    Http::assertSent(function (Request $request) {
        return $request->url() === 'https://api.cloudflare.com/client/v4/zones//purge_cache' &&
               $request->data() === ['purge_everything' => true];
    });

    expect($result)->toBe('request-id-123');
});

test('it throws exception on unsuccessful API response', function (): void {
    Http::fake([
        'https://api.cloudflare.com/client/v4/zones/*' => Http::response([
            'success' => false,
            'errors' => [
                [
                    'message' => 'Invalid API key',
                    'code' => 1000,
                ],
            ],
        ], 403),
    ]);
    
    $cloudflareCache = app()->make(CloudflareCacheInterface::class);
    
    expect(fn () => $cloudflareCache->purgeEverything())->toThrow(CloudflareCacheRequestException::class);
});

test('it returns false when API response success is false', function (): void {
    Http::fake([
        'https://api.cloudflare.com/client/v4/zones/*' => Http::response([
            'success' => false,
        ], 200),
    ]);
    
    $cloudflareCache = app()->make(CloudflareCacheInterface::class);
    $result = $cloudflareCache->purgeEverything();
    
    expect($result)->toBeFalse();
});

test('ive method returns correctly based on environment', function (): void {
    $cloudflareCache = app()->make(CloudflareCacheInterface::class);
    
    // Test for unit tests environment
    expect($cloudflareCache->ive())->toBeTrue();
    
    // Mock non-production without config
    config(['cloudflare-cache.api_key' => null]);
    config(['cloudflare-cache.identifier' => null]);
    config(['cloudflare-cache.debug' => false]);
    $this->app->detectEnvironment(fn () => 'staging');
    
    expect($cloudflareCache->ive())->toBeFalse();
    
    // Mock debug mode
    config(['cloudflare-cache.api_key' => 'test']);
    config(['cloudflare-cache.identifier' => 'test']);
    config(['cloudflare-cache.debug' => true]);
    
    expect($cloudflareCache->ive())->toBeTrue();
});
