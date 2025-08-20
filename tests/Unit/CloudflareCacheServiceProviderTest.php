<?php

use Fuelviews\CloudflareCache\CloudflareCacheInterface;
use Fuelviews\CloudflareCache\Commands\CloudflareCacheClearCommand;
use Fuelviews\CloudflareCache\Commands\CloudflareCacheStatusCommand;
use Fuelviews\CloudflareCache\Commands\CloudflareCacheValidateCommand;
use Fuelviews\CloudflareCache\Services\CloudflareServiceInterface;
use Spatie\LaravelPackageTools\Package;

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

    // Check that our commands are registered
    expect(array_key_exists('cloudflare-cache:clear', $commands))->toBeTrue();
    expect($commands['cloudflare-cache:clear'])->toBeInstanceOf(CloudflareCacheClearCommand::class);

    expect(array_key_exists('cloudflare-cache:status', $commands))->toBeTrue();
    expect($commands['cloudflare-cache:status'])->toBeInstanceOf(CloudflareCacheStatusCommand::class);

    expect(array_key_exists('cloudflare-cache:validate', $commands))->toBeTrue();
    expect($commands['cloudflare-cache:validate'])->toBeInstanceOf(CloudflareCacheValidateCommand::class);
});

test('service is bound as singleton', function () {
    // Test that the service is bound as a singleton
    $service1 = app()->make(CloudflareServiceInterface::class);
    $service2 = app()->make(CloudflareServiceInterface::class);

    expect($service1)->toBe($service2);
});

test('package is configured with spatie tools correctly', function () {
    // Create a mock of the Package class
    $package = Mockery::mock(Package::class);

    // Set up expectations for the package configuration
    $package->shouldReceive('name')->with('cloudflare-cache')->once()->andReturnSelf();
    $package->shouldReceive('hasConfigFile')->with('cloudflare-cache')->once()->andReturnSelf();
    $package->shouldReceive('publishesServiceProvider')->with('CloudflareCacheServiceProvider')->once()->andReturnSelf();
    $package->shouldReceive('hasCommand')->with(CloudflareCacheClearCommand::class)->once()->andReturnSelf();
    $package->shouldReceive('hasCommand')->with(CloudflareCacheStatusCommand::class)->once()->andReturnSelf();
    $package->shouldReceive('hasCommand')->with(CloudflareCacheValidateCommand::class)->once()->andReturnSelf();
    $package->shouldReceive('hasInstallCommand')->once()->andReturnSelf();

    // Create an instance of the service provider
    $serviceProvider = new \Fuelviews\CloudflareCache\CloudflareCacheServiceProvider(app());

    // Call the configurePackage method
    $serviceProvider->configurePackage($package);

    // Mockery will automatically verify that all expected methods were called
});
