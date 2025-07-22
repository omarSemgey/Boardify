<?php

namespace App\Services\Users;

class UserService
{
    public function getAssignedRoles($user){
        $roles = $user->roles()->get(); 

        if($roles->isEmpty()) return null;

        return $roles;
    }
}