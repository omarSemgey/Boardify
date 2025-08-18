<?php

namespace App\Boards\Services;

use App\Exceptions\ApiException;
use App\Boards\Models\Board;


class BoardService
{
    public function getBoardUsers(Board $board){
        try{
            $users = $board->uesrs()->get(); 
    
            if($users->isEmpty()) return null;
    
            return $users;
        }catch (\Throwable $err) {
            throw new ApiException('Fetching board users failed. Please try again later.', 500, $err);
        }
    }
}