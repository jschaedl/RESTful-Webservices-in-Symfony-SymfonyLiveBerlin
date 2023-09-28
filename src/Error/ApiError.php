<?php

declare(strict_types=1);

namespace App\Error;

final class ApiError
{
    public function __construct(
        private readonly string $message,
        private readonly string $detail
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }
}
