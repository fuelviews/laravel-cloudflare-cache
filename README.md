# Laravel cloudflare cache package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fuelviews/laravel-cloudflare-cache.svg?style=flat-square)](https://packagist.org/packages/fuelviews/laravel-cloudflare-cache)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/fuelviews/laravel-cloudflare-cache/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/fuelviews/laravel-cloudflare-cache/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/fuelviews/laravel-cloudflare-cache/fix-php-code-style-issues.yml?label=code%20style&style=flat-square)](https://github.com/fuelviews/laravel-cloudflare-cache/actions?query=workflow%3A"Fix+PHP+code+style+issues")
[![Total Downloads](https://img.shields.io/packagist/dt/fuelviews/laravel-cloudflare-cache.svg?style=flat-square)](https://packagist.org/packages/fuelviews/laravel-cloudflare-cache)

Laravel cloudflare cache package for laravel offers an efficient way to manage cloudflare's cache directly from your Laravel application, streamlining the purge process to ensure your content remains fresh.

## Installation

You can install the package via composer:

```bash
composer require fuelviews/laravel-cloudflare-cache
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="cloudflare-cache-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * Generate zone or global api key.
     *
     * @see https://dash.cloudflare.com/profile/api-tokens
     */
    'api_key' => env('CLOUDFLARE_CACHE_API_KEY'),

    /**
     * The zone_id of your site on cloudflare dashboard.
     */
    'identifier' => env('CLOUDFLARE_CACHE_ZONE_ID'),

    /**
     * Debug mode.
     */
    'debug' => env('CLOUDFLARE_CACHE_DEBUG', false),
];
```

## Usage

Purge everything function with:

```php
use Fuelviews\CloudflareCache\Facades\CloudflareCache;

CloudflareCache::purgeEverything();
```

Purge everything console command with:

```bash
php artisan cloudflare-cache:clear
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Thejmitchener](https://github.com/thejmitchener)
- [Fuelviews](https://github.com/fuelviews)
- [Yediyuz](https://github.com/yediyuz)
- [Mertasan](https://github.com/mertasan)
- [All Contributors](../../contributors)

## Support us

Fuelviews is a web development agency based in Portland, Maine. You'll find an overview of all our projects [on our website](https://fuelviews.com).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
