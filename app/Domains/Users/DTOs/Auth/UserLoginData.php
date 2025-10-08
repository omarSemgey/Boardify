<?php

namespace App\Domains\Users\DTOs\Auth;

class UserLoginData
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

        public function toAuthArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
