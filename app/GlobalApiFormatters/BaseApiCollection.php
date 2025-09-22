<?php

namespace App\GlobalApiFormatters;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;

class BaseApiCollection extends ResourceCollection
{
    protected string $status = 'success';
    protected ?string $message = null;
    protected int $httpCode = 200;

    public function withMessage(?string $message = null, int $httpCode = 200): static
    {
        $this->message = $message;
        $this->httpCode = $httpCode;
        return $this;
    }

    public function with($request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return parent::toResponse($request)->setStatusCode($this->httpCode);
    }
}
