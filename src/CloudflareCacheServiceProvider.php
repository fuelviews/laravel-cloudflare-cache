<?php

namespace Fuelviews\CloudflareCache;

use Fuelviews\CloudflareCache\Commands\CloudflareCacheClearCommand;
use Fuelviews\CloudflareCache\Commands\CloudflareCacheStatusCommand;
use Fuelviews\CloudflareCache\Commands\CloudflareCacheValidateCommand;
use Fuelviews\CloudflareCache\Services\CloudflareService;
use Fuelviews\CloudflareCache\Services\CloudflareServiceInterface;
use Illuminate\Http\Client\Factory;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CloudflareCacheServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('cloudflare-cache')
            ->hasConfigFile('cloudflare-cache')
            ->publishesServiceProvider('CloudflareCacheServiceProvider')
            ->hasCommand(CloudflareCacheClearCommand::class)
            ->hasCommand(CloudflareCacheStatusCommand::class)
            ->hasCommand(CloudflareCacheValidateCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Installing Laravel Cloudflare Cache package...');
                        $command->info('This package helps you purge Cloudflare cache from your Laravel application.');
                    })
                    ->publishConfigFile()
                    ->endWith(function (InstallCommand $command) {
                        $command->info('');
                        $command->info('🎉 Laravel Cloudflare Cache has been installed successfully!');
                        $command->info('');
                        $command->info('Next steps:');
                        $command->info('1. Add your Cloudflare credentials to your .env file:');
                        $command->info('   CLOUDFLARE_CACHE_API_KEY=your_api_key');
                        $command->info('   CLOUDFLARE_CACHE_ZONE_ID=your_zone_id');
                        $command->info('');
                        $command->info('2. Get your API key: https://dash.cloudflare.com/profile/api-tokens');
                        $command->info('3. Find your Zone ID in your Cloudflare dashboard');
                        $command->info('');
                        $command->info('4. Validate your configuration:');
                        $command->info('   php artisan cloudflare-cache:validate');
                        $command->info('');
                        $command->info('5. Test your setup:');
                        $command->info('   php artisan cloudflare-cache:status');
                    });
            });
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
        $this->validateConfiguration();
    }

    protected function validateConfiguration(): void
    {
        if (app()->environment('local', 'testing')) {
            $apiKey = config('cloudflare-cache.api_key');
            $zoneId = config('cloudflare-cache.identifier');

            if (empty($apiKey)) {
                report(new \InvalidArgumentException(
                    'Cloudflare Cache API key is not configured. Set CLOUDFLARE_CACHE_API_KEY in your .env file.'
                ));
            }

            if (empty($zoneId)) {
                report(new \InvalidArgumentException(
                    'Cloudflare Cache zone ID is not configured. Set CLOUDFLARE_CACHE_ZONE_ID in your .env file.'
                ));
            }

            if (! empty($zoneId) && ! preg_match('/^[a-f0-9]{32}$/', $zoneId)) {
                report(new \InvalidArgumentException(
                    'Cloudflare Cache zone ID format appears invalid. Should be a 32-character hexadecimal string.'
                ));
            }
        }
    }
}
