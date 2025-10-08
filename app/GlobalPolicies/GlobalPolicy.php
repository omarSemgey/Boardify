<?php

namespace App\GlobalPolicies;

use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserService;
use Illuminate\Database\Eloquent\Collection;

class GlobalPolicy
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function userHasAuthorityOver(User $user, object $model, string $typeName, string $permissionName): bool
    {
        $userBoards = $this->userService->getUserBoards($user);
        $userRoles  = $this->userService->getUserRoles($user);

        if ($userBoards->isEmpty() || $userRoles->isEmpty()) {
            return false;
        }

        $boardId = $model->board_id;
        $userIsMemberOfBoard = $this->userIsMemberOfBoard($userBoards, $boardId);

        if (!$userIsMemberOfBoard) {
            return false;
        }

        $roleAllowsAction = $this->roleAllowsAction($userRoles, $typeName, $permissionName);

        return $roleAllowsAction;
    }

    private function userIsMemberOfBoard(Collection $boards, int $boardId): bool
    {
        return $boards->pluck('id')->contains($boardId);
    }

    private function roleAllowsAction(Collection $roles, string $typeName, string $permissionName): bool
    {
        foreach ($roles as $role) {
            $matchesType = $role->type->name === $typeName;
            $hasPerm = $role->permissions->pluck('name')->contains($permissionName);

            if ($matchesType && $hasPerm) {
                return true;
            }
        }
        return false;
    }
}
