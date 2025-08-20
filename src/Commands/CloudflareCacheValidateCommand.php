<?php

namespace Fuelviews\CloudflareCache\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CloudflareCacheValidateCommand extends Command
{
    protected $signature = 'cloudflare-cache:validate';

    protected $description = 'Validate Cloudflare Cache configuration';

    public function handle(): int
    {
        $this->info('Validating Cloudflare Cache configuration...');

        $errors = [];
        $warnings = [];

        // Validate required configuration
        $this->validateRequiredConfig($errors);

        // Validate optional configuration
        $this->validateOptionalConfig($warnings);

        // Validate environment variables
        $this->validateEnvironmentVariables($errors, $warnings);

        // Display results
        $this->displayResults($errors, $warnings);

        return empty($errors) ? CommandAlias::SUCCESS : CommandAlias::FAILURE;
    }

    protected function validateRequiredConfig(array &$errors): void
    {
        $apiKey = config('cloudflare-cache.api_key');
        $zoneId = config('cloudflare-cache.identifier');

        if (empty($apiKey)) {
            $errors[] = 'API Key is required. Set CLOUDFLARE_CACHE_API_KEY in your .env file.';
        } elseif (strlen($apiKey) < 20) {
            $errors[] = 'API Key appears to be invalid (too short). Please verify your API key.';
        }

        if (empty($zoneId)) {
            $errors[] = 'Zone ID is required. Set CLOUDFLARE_CACHE_ZONE_ID in your .env file.';
        } elseif (! preg_match('/^[a-f0-9]{32}$/', $zoneId)) {
            $errors[] = 'Zone ID format appears invalid. Should be a 32-character hexadecimal string.';
        }
    }

    protected function validateOptionalConfig(array &$warnings): void
    {
        $debug = config('cloudflare-cache.debug');

        if ($debug && app()->environment('production')) {
            $warnings[] = 'Debug mode is enabled in production environment. Consider disabling it.';
        }
    }

    protected function validateEnvironmentVariables(array &$errors, array &$warnings): void
    {
        $envVars = [
            'CLOUDFLARE_CACHE_API_KEY',
            'CLOUDFLARE_CACHE_ZONE_ID',
        ];

        foreach ($envVars as $var) {
            if (! env($var)) {
                $warnings[] = "Environment variable {$var} is not set.";
            }
        }

        // Check if .env file exists
        if (! file_exists(base_path('.env'))) {
            $warnings[] = '.env file not found. Make sure environment variables are properly configured.';
        }
    }

    protected function displayResults(array $errors, array $warnings): void
    {
        if (empty($errors) && empty($warnings)) {
            $this->info('✅ All configuration checks passed!');

            return;
        }

        if (! empty($errors)) {
            $this->error("\n❌ Configuration Errors:");
            foreach ($errors as $error) {
                $this->error("   • {$error}");
            }
        }

        if (! empty($warnings)) {
            $this->warn("\n⚠️  Configuration Warnings:");
            foreach ($warnings as $warning) {
                $this->warn("   • {$warning}");
            }
        }

        if (empty($errors)) {
            $this->info("\n✅ Configuration is valid with some warnings.");
        } else {
            $this->error("\n❌ Configuration validation failed. Please fix the errors above.");
        }
    }
}
