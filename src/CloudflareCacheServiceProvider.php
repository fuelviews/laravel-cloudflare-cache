<?php

namespace Fuelviews\CloudflareCache;

use Fuelviews\CloudflareCache\Services\CloudflareService;
use Fuelviews\CloudflareCache\Services\CloudflareServiceInterface;
use Illuminate\Http\Client\Factory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CloudflareCacheServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cloudflare-cache')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->registerClient()
            ->registerCloudflareCache();
    }

    public function registerClient(): static
    {
        $this->app->bind('cloudflare-cache.client', function ($app): Factory {
            return $app[Factory::class];
        });

        $this->app->singleton(CloudflareServiceInterface::class, function ($app): CloudflareService {
            return new CloudflareService(
                $app->make('cloudflare-cache.client'),
                config('cloudflare-cache.api_key'),
                config('cloudflare-cache.identifier'),
            );
        });

        return $this;
    }

    public function registerCloudflareCache(): void
    {
        $this->app->bind(CloudflareCacheInterface::class, function ($app): CloudflareCache {
            return new CloudflareCache(
                $app->make(CloudflareServiceInterface::class)
            );
        });

        $this->app->alias(CloudflareCacheInterface::class, 'cloudflare-cache');
    }
}
