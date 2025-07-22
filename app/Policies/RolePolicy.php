<?php

namespace App\Policies;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function getAssignedUsers(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, StoreRoleRequest $request): bool
    {
        $userRoles = $user->roles()->with('permissions')->get();

        foreach ($userRoles as $userRole){
        $isBoardRole = $userRole->type === 'board';
        $sameBoard = $userRole->board_id === $request->board_id;
        $hasPermission = $userRole->permissions->contains('name','create_role');

        if ($isBoardRole && $sameBoard && $hasPermission){
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
        $userRoles = $user->roles()->with('permissions')->get();

        foreach ($userRoles as $userRole){
            $isBoardRole = $role->type === 'board';
            $sameBoard = $userRole->board_id === $role->board_id;
            $hasPermission = $userRole->permissions->contains('name','update_role');

            if ($isBoardRole && $sameBoard && $hasPermission){
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
        $userRoles = $user->roles()->with('permissions')->get();

        foreach ($userRoles as $userRole){
            $isBoardRole = $role->type === 'board';
            $sameBoard = $userRole->board_id === $role->board_id;
            $hasPermission = $userRole->permissions->contains('name','delete_role');

            if ($isBoardRole && $sameBoard && $hasPermission){
                return true;
            }

        }

        return false;
    }

}
