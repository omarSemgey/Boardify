<?php

namespace App\Users\Services;

use App\Users\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getAssignedRoles(User $user){
        try{
            $roles = $user->roles()->get(); 
            
            if($roles->isEmpty()) return null;
            
            return $roles;
        }catch (\Throwable $err) {
            Log::error('Fetching users roles failed. ', ['error' => $err]);
            throw new \Exception('Fetching users roles failed. Please try again');
        }
    }
}