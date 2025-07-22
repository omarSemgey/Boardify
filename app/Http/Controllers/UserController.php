<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\BaseApiResource;
use App\Models\User;
use App\Services\Users\UserCrudService;
use App\Services\Users\UserService;

use Request;
use Validator;

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
        try{
            $createdUser = $this->userCrudService->store($request->validated());
                
            return (new BaseApiResource($createdUser))->withMessage('User created successfully',201);
        }catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'User creation failed. Please try again later.',
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $updatedUser = $this->userCrudService->update($request->validated(),$user);

            return (new BaseApiResource($updatedUser))->withMessage('User updated successfully', 200);

        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'User update failed. Please try again later.'
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try{

            $user = $this->userCrudService->destroy($user);

            return (new BaseApiResource($user))->withMessage('User deleted successfully',200);

        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'User deletion failed. Please try again'
            ], 500);
        }
    }

    public function getAssignedRoles(User $user) {
        $roles= $this->userService->getAssignedRoles($user);

        if (is_null($roles)) {
            return response()->json([
                'status' => 'failed', 
                'message' => 'No roles are assigned to this user.',
                'data' => [],
            ], 204);
        }

        return BaseApiResource::collection($roles)->withMessage('roles assigned to this user.',200);
    }
    
    //TODO: get a better name for the function
    public function userSearch(Request $request) {
        try{
            //TODO: learn if its better to have a seperate validation request or just validate it in the controller
 
            $searchTerm = str_replace(['%', '_'], '', $request->input('search', ''));

            $searchTerm = trim($searchTerm);

            $validator = Validator::make(
                ['search' => $searchTerm,],
                ['search' => 'bail|required|string|min:3|max:30|regex:/^[\p{L}0-9_\s]{3,30}$/u',]
            );

            $validatedSearch = $validator->validated();

            $searchTerm = $validatedSearch['search'];

            $users = User::where(function($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%");
            })->orderByRaw('
                CASE 
                    WHEN name LIKE ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN name LIKE ? THEN 3
                    ELSE 4
                END ASC
            ', [$searchTerm, $searchTerm.'%', '%'.$searchTerm.'%']
            )->paginate(10);

            if(empty($users)) {
                return response()->json([
                    'status' => 'failed', 
                    'message' => 'No users were found.',
                    'data' => [],
                ], 204);
            }

            return BaseApiResource::collection($users)->withMessage('Users were found.',200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search failed'
            ], 500);
        }
    }
}