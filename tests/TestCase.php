<?php

namespace Fuelviews\CloudflareCache\Tests;

use Fuelviews\CloudflareCache\CloudflareCacheServiceProvider;
use Illuminate\Encryption\Encrypter;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

    }

    protected function getPackageProviders($app)
    {
        return [
            CloudflareCacheServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('app.key', 'base64:'.base64_encode(Encrypter::generateKey(config()['app.cipher'])));

        config()->set('cloudflare-cache.api_key', '');
        config()->set('cloudflare-cache.identifier', '');
    }
}
