<?php

namespace App\Services\Roles;

class RoleService
{
    public function getAssignedUsers($role)
    {
        $users = $role->users()->get(); 

        if($users->isEmpty()) return null;

        return $users;
    }
}