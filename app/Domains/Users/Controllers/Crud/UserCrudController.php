<?php

namespace App\Domains\Users\Controllers\Crud;

use App\Domains\Users\Requests\Crud\UserUpdateRequest;

use App\GlobalApiFormatters\BaseApiResource;
use App\Domains\Users\Services\Crud\UserCrudService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserCrudController extends Controller
{
    protected UserCrudService $userCrudService;

    public function __construct(UserCrudService $userCrudService) {
        $this->userCrudService = $userCrudService;
    }

    public function update(UserUpdateRequest $request)
    {
        $dto = $request->toDTO();
        $user = $request->user();
        $updatedUser = $this->userCrudService->update($dto,$user);

        return new BaseApiResource($updatedUser)->withMessage('User updated successfully.', 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        $this->userCrudService->destroy($user);

        return (new BaseApiResource($user))->withMessage('User deleted successfully.',200);
    }
}