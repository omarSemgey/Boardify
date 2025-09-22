<?php

namespace App\Domains\Users\Policies;

use App\Domains\Users\Models\User;

class UserPolicy
{
    public function manageSelf(User $requestUser, User $targetUser): bool
    {
        return $requestUser->id === $targetUser->id;
    }
}