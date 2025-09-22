<?php

namespace App\Domains\Roles\Policies;

use App\Domains\Roles\Requests\StoreRoleRequest;
use App\Domains\Roles\Models\Role;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserService;

class RolePolicy
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Determine whether the user can view the model.
     */
    public function getAssignedUsers(User $user, Role $role): bool
    {
        $roleBoard = $role->board_id;
        $userBoards = $this->userService->getUserBoards($user);

        foreach($userBoards as $userBoard)
        if($roleBoard === $userBoard->id){
            return true;
        }
        return false;
    }

    /**  
     * Determine whether the user can create models.
     */
    public function create(User $user, StoreRoleRequest $request): bool
    {
        $userRoles = $this->userService->getUserRoles($user);

        foreach ($userRoles as $userRole) {
            $isRoleType = $userRole->type->name === 'rule';
            $sameBoard = $userRole->board_id === $request->board_id;
            $hasPermission = $userRole->permissions->pluck('name')->contains('create');

            if ($isRoleType && $sameBoard && $hasPermission) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        $userRoles = $user->roles()->with(['permissions', 'type'])->get();

        foreach ($userRoles as $userRole) {
            $isRoleType = $userRole->type->name === 'rule';
            $sameBoard = $userRole->board_id === $role->board_id;
            $hasPermission = $userRole->permissions->pluck('name')->contains('update');

            if ($isRoleType && $sameBoard && $hasPermission) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Role $role): bool
    {
        $userRoles = $user->roles()->with(['permissions', 'type'])->get();

        foreach ($userRoles as $userRole) {
            $isRoleType = $userRole->type->name === 'rule';
            $sameBoard = $userRole->board_id === $role->board_id;
            $hasPermission = $userRole->permissions->pluck('name')->contains('delete');

            if ($isRoleType && $sameBoard && $hasPermission) {
                return true;
            }
        }

        return false;
    }
}
