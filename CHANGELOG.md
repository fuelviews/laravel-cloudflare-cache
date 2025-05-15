# Changelog

All notable changes to `laravel-cloudflare-cache` will be documented in this file.

## v0.0.12 - 2025-05-15

### What's Changed

* Update GitHub Actions workflow to restrict permissions for pull requests and contents to read-only, enhancing security. Removed unnecessary configuration for GitHub token setup. by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/27
* Update the changelog to reflect recent changes, including version bumps and enhancements to the codebase. Adjust README badges for accuracy and improve the clarity of the Cloudflare cache command documentation. by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/28

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.11...v0.0.12

## v0.0.11 - 2025-05-14

### What's Changed

* Fix tests workflow by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/26

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.10...v0.0.11

## v0.0.10 - 2025-05-14

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/fuelviews/laravel-cloudflare-cache/pull/24
* Add PHP CS Fixer configuration and update .gitignore to include its cache file; update README badges to point to the correct workflows, and refactor code for improved clarity and consistency. Additionally, enhance tests for command execution and service provider registration. by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/25

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.9...v0.0.10

## v0.0.9 - 2025-03-14

### What's Changed

* Bump stefanzweifel/git-auto-commit-action from 4 to 5 by @dependabot in https://github.com/fuelviews/laravel-cloudflare-cache/pull/22
* Bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/fuelviews/laravel-cloudflare-cache/pull/23

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.8...v0.0.9

## v0.0.8 - 2024-12-19

### What's Changed

* Refactor CloudflareCacheServiceProvider packageBooted method. by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/21

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.7...v0.0.8

## v0.0.7 - 2024-12-18

### What's Changed

* Clear Cloudflare cache if the command exists, otherwise log a warning message. The changes were made to handle the case when the cloudflare-cache:clear command does not exist. by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/20

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.6...v0.0.7

## v0.0.6 - 2024-12-18

### What's Changed

* Add Artisan facade and clear cache and optimize commands in the packageBooted method of the CloudflareCacheServiceProvider class. These changes were made to improve the performance and clear the cache in the development and production environments. by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/19

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.5...v0.0.6

## v0.0.5 - 2024-12-18

### What's Changed

* Bump anothrNick/github-tag-action from 1.69.0 to 1.71.0 by @dependabot in https://github.com/fuelviews/laravel-cloudflare-cache/pull/12
* Bump poseidon/wait-for-status-checks from 0.5.0 to 0.6.0 by @dependabot in https://github.com/fuelviews/laravel-cloudflare-cache/pull/14
* Bump poseidon/wait-for-status-checks from 0.5.0 to 0.6.0 by @dependabot in https://github.com/fuelviews/laravel-cloudflare-cache/pull/15
* Purged Cloudflare cache and added success message by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/16

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.4...v0.0.5

## v0.0.4 - 2024-06-14

### What's Changed

* Ignore specific files and directories in Git. Updated package name and added homepage URL by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/9

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.3...v0.0.4

## v0.0.3 - 2024-06-05

### What's Changed

* Update readme and deps by @thejmitchener in https://github.com/fuelviews/laravel-cloudflare-cache/pull/8

**Full Changelog**: https://github.com/fuelviews/laravel-cloudflare-cache/compare/v0.0.2...v0.0.3
