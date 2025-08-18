<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected string $status = 'failed';
    protected int $httpCode;
    protected ?string $err;

    public function __construct(string $message, int $httpCode = 500, ?\Throwable $err = null)
    {
        parent::__construct($message);

        $this->httpCode = $httpCode;
        $this->err = $err ? $err->getMessage() : null;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getErr(): ?string
    {
        return $this->err;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->getMessage(),
        ];
    }
}
