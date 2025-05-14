<?php

namespace Fuelviews\CloudflareCache;

use Fuelviews\CloudflareCache\Commands\CloudflareCacheClearCommand;
use Fuelviews\CloudflareCache\Services\CloudflareService;
use Fuelviews\CloudflareCache\Services\CloudflareServiceInterface;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Artisan;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CloudflareCacheServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('cloudflare-cache')
            ->hasConfigFile('cloudflare-cache')
            ->hasCommand(
                CloudflareCacheClearCommand::class,
            );
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

        $this->app->singleton(CloudflareServiceInterface::class, function ($app): \Fuelviews\CloudflareCache\Services\CloudflareServiceInterface {
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
        $this->app->bind(CloudflareCacheInterface::class, function ($app): \Fuelviews\CloudflareCache\CloudflareCacheInterface {
            return new CloudflareCache(
                $app->make(CloudflareServiceInterface::class)
            );
        });

        $this->app->alias(CloudflareCacheInterface::class, 'cloudflare-cache');
    }

    public function packageBooted(): void
    {
        if (app()->environment('development', 'production')) {
            if (class_exists(\RalphJSmit\Glide\Glide::class)) {
                dispatch(function () {
                    Artisan::call('glide:clear');
                })->delay(now()->addSeconds(30));
            }

            if (array_key_exists('cloudflare-cache:clear', Artisan::all())) {
                dispatch(function () {
                    Artisan::call('cloudflare-cache:clear');
                })->delay(now()->addSeconds(30));
            }
        }
    }

}
