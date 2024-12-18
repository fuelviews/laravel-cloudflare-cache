<?php

namespace Fuelviews\CloudflareCache\Commands;

use Fuelviews\CloudflareCache\Exceptions\CloudflareCacheRequestException;
use Fuelviews\CloudflareCache\Facades\CloudflareCache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CloudflareCacheClearCommand extends Command
{
    protected $signature = 'cloudflare-cache:clear';

    protected $description = 'Cloudflare purge everything';

    /**
     * Execute the console command.
     *
     * This method handles the logic after the command is called. It decides
     * whether to include an index in the sitemap based on configuration settings.
     * Depending on those settings, it may generate individual sitemaps for pages
     * and posts and then either create a sitemap index to include them or
     * directly generate a single sitemap.
     */
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
        } catch (CloudflareCacheRequestException $e) {
            $this->error("Error purging Cloudflare cache: {$e->getMessage()}");

            return CommandAlias::FAILURE;
        }
    }
}
