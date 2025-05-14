<?php

use Fuelviews\CloudflareCache\CloudflareCacheInterface;
use Fuelviews\CloudflareCache\Exceptions\CloudflareCacheRequestException;
use Illuminate\Console\Command;

test('command succeeds when purge is successful', function () {
    // Mock the CloudflareCache facade
    $mock = Mockery::mock(CloudflareCacheInterface::class);
    $mock->shouldReceive('purgeEverything')
        ->once()
        ->andReturn('success-id-123');
    
    $this->app->instance(CloudflareCacheInterface::class, $mock);
    
    // Run the command
    $this->artisan('cloudflare-cache:clear')
        ->expectsOutput('Cloudflare cache successfully purged.')
        ->assertExitCode(Command::SUCCESS);
});

test('command fails when purge returns false', function () {
    // Mock the CloudflareCache facade
    $mock = Mockery::mock(CloudflareCacheInterface::class);
    $mock->shouldReceive('purgeEverything')
        ->once()
        ->andReturn(false);
    
    $this->app->instance(CloudflareCacheInterface::class, $mock);
    
    // Run the command
    $this->artisan('cloudflare-cache:clear')
        ->expectsOutput('Failed to purge Cloudflare cache.')
        ->assertExitCode(Command::FAILURE);
});

test('command fails and displays error when exception is thrown', function () {
    // Mock the CloudflareCache facade
    $mock = Mockery::mock(CloudflareCacheInterface::class);
    $mock->shouldReceive('purgeEverything')
        ->once()
        ->andThrow(CloudflareCacheRequestException::requestError(403, 'API authentication error', 9103));
    
    $this->app->instance(CloudflareCacheInterface::class, $mock);
    
    // Run the command
    $this->artisan('cloudflare-cache:clear')
        ->expectsOutput('Error purging Cloudflare cache: Request error: API authentication error | Code: 9103')
        ->assertExitCode(Command::FAILURE);
});
