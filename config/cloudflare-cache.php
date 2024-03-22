<?php

return [
    /**
     * Generate global api key.
     *
     * @see https://dash.cloudflare.com/profile/api-tokens
     */
    'api_key' => env('CLOUDFLARE_CACHE_KEY'),

    /**
     * zone_id of your site on cloudflare dashboard.
     */
    'identifier' => env('CLOUDFLARE_CACHE_IDENTIFIER'),

    'debug' => env('CLOUDFLARE_CACHE_DEBUG', false),
];
