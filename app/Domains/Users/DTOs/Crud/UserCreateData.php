<?php

namespace App\Domains\Users\DTOs\Crud;

class UserCreateData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?object $profile = null // file upload optional
    ) {}

    public function toAuthArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            profile: $data['profile'] ?? null
        );
    }
}
