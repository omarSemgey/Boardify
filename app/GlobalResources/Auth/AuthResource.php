<?php

namespace App\GlobalResources\Resources\Auth;

use App\GlobalResources\Resources\BaseApiResource;

class AuthResource extends BaseApiResource
{
    public function toArray($request): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'profile' => $this->profile
            ],
            'authorization' => [
                'type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ];
    }
}
