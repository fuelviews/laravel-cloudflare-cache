<?php

namespace Fuelviews\CloudflareCache\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fuelviews\CloudflareCache\CloudflareCache
 *
 * @method static bool|string purgeEverything()
 */
class CloudflareCache extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cloudflare-cache';
    }
}
