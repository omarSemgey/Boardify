<?php

namespace App\Domains\Users\Controllers\Curd;

use App\Domains\Users\Requests\Crud\UserUpdateRequest;
use App\GlobalApiFormatters\BaseApiResource;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\Crud\UserCrudService;
use App\Http\Controllers\Controller;

class UserCrudController extends Controller
{
    protected UserCrudService $userCrudService;

    public function __construct(UserCrudService $userCrudService) {
        $this->userCrudService = $userCrudService;
    }

    public function update(UserUpdateRequest $request, User $user)
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