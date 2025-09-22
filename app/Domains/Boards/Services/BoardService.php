<?php

namespace App\Domains\Boards\Services;

use App\GlobalExceptions\ApiException;
use App\Domains\Boards\Models\Board;


class BoardService
{
    public function getBoardUsers(Board $board){
        try{
            $users = $board->users()->get(); 
    
            if($users->isEmpty()) return null;
    
            return $users;
        }catch (\Throwable $err) {
            throw new ApiException('Fetching board users failed. Please try again later.', 500, $err);
        }
    }
}