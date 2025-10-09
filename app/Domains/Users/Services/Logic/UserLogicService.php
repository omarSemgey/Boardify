<?php

namespace App\Domains\Users\Services\Logic;

use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\Log;

class UserLogicService
{
    public function getUserBoards(User $user){
        try{
            $boards = $user->boards()->get(); 
            
            if($boards->isEmpty()) return null;
            
            return $boards;
        }catch (\Throwable $err) {
            Log::error('Fetching users boards failed. ', ['error' => $err]);
            throw new \Exception('Fetching users boards failed. Please try again');
        }
    }

    public function getUserRoles(User $user){
        try{
            $roles = $user->roles()->with(['permissions', 'type'])->get();
            
            if($roles->isEmpty()) return null;
            
            return $roles;
        }catch (\Throwable $err) {
            Log::error('Fetching users roles failed. ', ['error' => $err]);
            throw new \Exception('Fetching users roles failed. Please try again');
        }
    }
}