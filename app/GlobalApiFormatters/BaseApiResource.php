<?php

namespace App\GlobalApiFormatters;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;

class BaseApiResource extends JsonResource
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
        // this overrides the default 200 response
        return parent::toResponse($request)->setStatusCode($this->httpCode);
    }
}
