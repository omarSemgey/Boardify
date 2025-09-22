<?php

namespace App\Domains\Roles\Services;

class RoleService
{
    public function getAssignedUsers($role)
    {
        $users = $role->users()->get(); 

        if($users->isEmpty()) return [];

        return $users;
    }
}