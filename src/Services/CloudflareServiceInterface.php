<?php

namespace Fuelviews\CloudflareCache\Services;

use Illuminate\Http\Client\Response;

interface CloudflareServiceInterface
{
    /**
     * @param  string[]|array<string, bool>|string[][]  $data
     */
    public function post(string $endpoint, array $data = []): Response;
}
