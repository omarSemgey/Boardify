<?php

namespace App\Domains\Users\Controllers;

use App\GlobalApiFormatters\BaseApiResource;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserCrudService;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService) {
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