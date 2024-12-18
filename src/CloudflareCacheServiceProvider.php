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
            ->name('laravel-cloudflare-cache')
            ->hasConfigFile()
            ->hasCommand(CloudflareCacheClearCommand::class,
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

    public function packageBooted(): void
    {
        if (! app()->environment('local')) {
            if (file_exists(config_path('glide.php'))) {
                Artisan::call('glide:clear');
            }

            if (file_exists(config_path('cloudflare-cache.php'))) {
                Artisan::call('optimize:clear');
                Artisan::call('optimize');
                sleep(5);
                Artisan::call('cloudflare-cache:clear');
            }
        }
    }
}
