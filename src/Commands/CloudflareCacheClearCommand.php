<?php

namespace Fuelviews\CloudflareCache\Commands;

use Fuelviews\CloudflareCache\Exceptions\CloudflareCacheRequestException;
use Fuelviews\CloudflareCache\Facades\CloudflareCache;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CloudflareCacheClearCommand extends Command
{
    protected $signature = 'cloudflare-cache:clear';

    protected $description = 'Cloudflare cache purge everything';

    public function handle(): int
    {
        try {
            $result = CloudflareCache::purgeEverything();

            if ($result === false) {
                $this->error('Failed to purge Cloudflare cache.');

                return CommandAlias::FAILURE;
            }

            $this->info('Cloudflare cache successfully purged.');

            return CommandAlias::SUCCESS;
        } catch (CloudflareCacheRequestException $cloudflareCacheRequestException) {
            $this->error('Error purging Cloudflare cache: ' . $cloudflareCacheRequestException->getMessage());

            return CommandAlias::FAILURE;
        }
    }
}
