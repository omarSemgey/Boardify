<?php

namespace App\Policies;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    public function manageSelf(User $requestUser, User $targetUser): bool
    {
        return $requestUser->id === $targetUser->id;
    }
}