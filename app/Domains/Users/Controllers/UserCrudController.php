<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\Requests\StoreUserRequest;
use App\Domains\Users\Requests\UpdateUserRequest;
use App\GlobalApiFormatters\BaseApiResource;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserCrudService;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;

class UserCrudController extends Controller
{
    protected UserCrudService $userCrudService;

    public function __construct(UserCrudService $userCrudService) {
        $this->userCrudService = $userCrudService;
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $dto = $request->toDTO();
        $updatedUser = $this->userCrudService->update($dto,$user);

        return new BaseApiResource($updatedUser)->withMessage('User updated successfully', 200);
    }

    public function destroy(User $user)
    {
        $user = $this->userCrudService->destroy($user);

        return (new BaseApiResource($user))->withMessage('User deleted successfully',200);
    }
}