<?php

namespace App\Domains\Users\Contracts\Repositories\Crud;

use App\Domains\Users\Models\User;

interface UserCrudRepositoryInterface
{
    public function create(array $data): User;

    public function update(User $user, array $data): bool;

    public function delete(User $user): bool;
}