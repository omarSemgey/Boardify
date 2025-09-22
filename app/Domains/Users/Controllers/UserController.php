<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\Requests\StoreUserRequest;
use App\Domains\Users\Requests\UpdateUserRequest;
use App\GlobalApiFormatters\BaseApiResource;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserCrudService;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected UserCrudService $userCrudService;
    protected UserService $userService;

    public function __construct(UserCrudService $userCrudService,UserService $userService) {
        $this->userCrudService = $userCrudService;
        $this->userService = $userService;
    }

    public function store(StoreUserRequest $request)
    {
        $createdUser = $this->userCrudService->store($request->validated());
            
        return new BaseApiResource($createdUser)->withMessage('User created successfully',201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $updatedUser = $this->userCrudService->update($request->validated(),$user);

        return new BaseApiResource($updatedUser)->withMessage('User updated successfully', 200);
    }

    public function destroy(User $user)
    {
        $user = $this->userCrudService->destroy($user);

        return (new BaseApiResource($user))->withMessage('User deleted successfully',200);
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