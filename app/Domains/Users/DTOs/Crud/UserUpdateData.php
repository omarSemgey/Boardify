<?php

namespace App\Domains\Users\DTOs\Crud;

use Illuminate\Http\UploadedFile;
use ReflectionClass;

class UserUpdateData
{
    protected array $excludedColumns = ['profile', 'password'];

    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?UploadedFile $profile = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            profile: $data['profile'] ?? null
        );
    }

    /**
     * Returns all non-null fields except those in excludedColumns
     */
    public function toArray(): array
    {
        $data = [];
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            $value = $this->$name;

            if ($value !== null && !in_array($name, $this->excludedColumns, true)) {
                $data[$name] = $value;
            }
        }

        return $data;
    }

    /**
     * Optionally, allow modifying excluded columns dynamically
     */
    public function setExcludedColumns(array $columns): void
    {
        $this->excludedColumns = $columns;
    }
}