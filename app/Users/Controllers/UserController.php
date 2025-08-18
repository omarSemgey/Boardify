<?php

namespace App\Users\Controllers;

use App\Users\Requests\StoreUserRequest;
use App\Users\Requests\UpdateUserRequest;
use App\GlobalResources\Resources\BaseApiResource;
use App\Users\Models\User;
use App\Users\Services\UserCrudService;
use App\Users\Services\UserService;
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

    public function getAssignedRoles(User $user) {
        $roles = $this->userService->getAssignedRoles($user);

        return BaseApiResource::collection($roles)->withMessage('roles assigned to this user.',200);
    }   
}