<?php

namespace App\User\Policies;

use App\Users\Models\User;

class UserPolicy
{
    public function manageSelf(User $requestUser, User $targetUser): bool
    {
        return $requestUser->id === $targetUser->id;
    }
}