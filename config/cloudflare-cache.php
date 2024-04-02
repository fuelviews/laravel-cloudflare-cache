<?php

return [
    /**
     * Generate zone or global api key.
     *
     * @see https://dash.cloudflare.com/profile/api-tokens
     */
    'api_key'    => env('CLOUDFLARE_CACHE_API_KEY'),

    /**
     * The zone_id of your site on cloudflare dashboard.
     */
    'identifier' => env('CLOUDFLARE_CACHE_ZONE_ID'),

    /**
     * Debug mode.
     */
    'debug'      => env('CLOUDFLARE_CACHE_DEBUG', false),
];
