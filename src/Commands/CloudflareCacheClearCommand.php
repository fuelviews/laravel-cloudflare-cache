<?php

namespace Fuelviews\CloudflareCache\Commands;

use Fuelviews\CloudflareCache\Facades\CloudflareCache;
use Illuminate\Console\Command;

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
    public function handle(): bool
    {
        CloudflareCache::purgeEverything();

        return true;
    }
}
