<?php

namespace App\GlobalPolicies;

use App\Domains\Users\Models\User;
use App\Domains\Users\Services\Logic\UserLogicService;
use Illuminate\Database\Eloquent\Collection;

class GlobalPolicy
{
    protected UserLogicService $userLogicService;

    public function __construct(UserLogicService $userLogicService)
    {
        $this->userLogicService = $userLogicService;
    }

    public function userHasAuthorityOver(User $user, object $model, string $typeName, string $permissionName): bool
    {
        $userBoards = $this->userLogicService->getUserBoards($user);
        $userRoles  = $this->userLogicService->getUserRoles($user);

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
        return $roles->contains(function ($role) use ($typeName, $permissionName) {
            return $role->type->name === $typeName
                && $role->permissions->pluck('name')->contains($permissionName);
        });
    }
}
