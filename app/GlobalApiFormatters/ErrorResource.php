<?php

namespace App\GlobalApiFormatters;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;

class ErrorResource extends JsonResource
{
    protected int $httpCode = 500;

    public function withHttpCode(int $httpCode)
    {
        return response()->json($this->toArray(request()), $httpCode);
    }

    public function toArray($request): array
    {
        return [
            'status' => 'failed',
            'message' => $this->resource['message'] ?? 'Something went wrong',
            'errors' => $this->resource['errors'] ?? null,
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return parent::toResponse($request)->setStatusCode($this->httpCode);
    }
}
