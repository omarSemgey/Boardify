<?php

namespace App\Domains\Users\Controllers\Logic;

use App\GlobalApiFormatters\BaseApiResource;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\Crud\UserCrudService;
use App\Domains\Users\Services\Logic\UserLogicService;
use App\Http\Controllers\Controller;

class UserLogicController extends Controller
{
    protected UserLogicService $userService;

    public function __construct(UserLogicService $userService) {
        $this->userService = $userService;
    }

    public function getUserRoles(User $user) {
        $roles = $this->userService->getUserRoles($user);

        return BaseApiResource::collection($roles)->withMessage('roles assigned to this user.',200);
    }   

    public function getUserBoards(User $user) {
        $roles = $this->userService->getUserBoards($user);

        return BaseApiResource::collection($roles)->withMessage('roles assigned to this user.',200);
    }
}