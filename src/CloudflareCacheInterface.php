<?php

namespace Fuelviews\CloudflareCache;

interface CloudflareCacheInterface
{
    public function purgeEverything(): bool|string;
}
