<?php

namespace App\GlobalPolicies;

use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserService;

class GlobalPolicy
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function hasPermission(User $user, $model, string $typeName, string $permissionName): bool
    {
        $userBoards = $this->userService->getUserBoards($user);
        $userRoles = $this->userService->getUserRoles($user);

        if (is_null($userBoards) || is_null($userRoles)) return false;

        $modelBoardId = $model->board_id;
        if (!$userBoards->pluck('id')->contains($modelBoardId)) {
            return false;
        }

        foreach ($userRoles as $userRole) {
            $matchesType = $userRole->type->name === $typeName;
            $hasPerm = $userRole->permissions->pluck('name')->contains($permissionName);

            if ($matchesType && $hasPerm) {
                return true;
            }
        }

        return false;
    }
}
