<?php

namespace Fuelviews\CloudflareCache\Exceptions;

use RuntimeException;

final class CloudflareCacheRequestException extends RuntimeException
{
    public static function requestError(int $status, string $message, ?int $code): self
    {
        return new self(
            message: 'Request error: '.$message.' | Code: '.$code,
            code: $status
        );
    }
}
