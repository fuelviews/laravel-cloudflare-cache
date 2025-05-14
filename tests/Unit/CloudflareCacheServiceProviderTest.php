<?php

use Fuelviews\CloudflareCache\CloudflareCacheInterface;
use Fuelviews\CloudflareCache\Commands\CloudflareCacheClearCommand;
use Fuelviews\CloudflareCache\Services\CloudflareServiceInterface;

test('service provider registers bindings correctly', function () {
    // Test that the interfaces are bound correctly
    expect(app()->bound(CloudflareCacheInterface::class))->toBeTrue();
    expect(app()->bound(CloudflareServiceInterface::class))->toBeTrue();
    
    // Test that bound implementation is correct
    $cloudflareService = app()->make(CloudflareServiceInterface::class);
    expect($cloudflareService)->toBeInstanceOf(\Fuelviews\CloudflareCache\Services\CloudflareService::class);
    
    $cloudflareCache = app()->make(CloudflareCacheInterface::class);
    expect($cloudflareCache)->toBeInstanceOf(\Fuelviews\CloudflareCache\CloudflareCache::class);
});

test('service provider registers config correctly', function () {
    // Assert config is registered
    expect(config()->has('cloudflare-cache'))->toBeTrue();
    expect(config()->has('cloudflare-cache.api_key'))->toBeTrue();
    expect(config()->has('cloudflare-cache.identifier'))->toBeTrue();
    expect(config()->has('cloudflare-cache.debug'))->toBeTrue();
});

test('service provider registers commands correctly', function () {
    // Get all registered commands
    $commands = Artisan::all();
    
    // Check that our command is registered
    expect(array_key_exists('cloudflare-cache:clear', $commands))->toBeTrue();
    expect($commands['cloudflare-cache:clear'])->toBeInstanceOf(CloudflareCacheClearCommand::class);
});

test('service is bound as singleton', function () {
    // Test that the service is bound as a singleton
    $service1 = app()->make(CloudflareServiceInterface::class);
    $service2 = app()->make(CloudflareServiceInterface::class);
    
    expect($service1)->toBe($service2);
});