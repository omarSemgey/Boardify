<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\BaseApiResource;
use App\Models\Role;
use App\Services\Roles\RoleCrudService;
use App\Services\Roles\RoleService;

class RoleController extends Controller
{
    protected RoleCrudService $roleCrudService;
    protected RoleService $roleService;

    public function __construct(RoleCrudService $roleCrudService,RoleService $roleService) {
        $this->roleCrudService;
        $this->roleService;
    }

    public function store(StoreRoleRequest $request)
    {
        try{
            $createdRole = $this->roleCrudService->store($request->validated());
                
            return (new BaseApiResource($createdRole))->withMessage('Role created successfully',201);
        }catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role creation failed. Please try again later.',
            ], 500);
        }
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $updatedRole = $this->roleCrudService->update($request->validated(),$role);

            return (new BaseApiResource($updatedRole))->withMessage('Role updated successfully', 200);

        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role update failed. Please try again later.'
            ], 500);
        }
    }

    public function destroy(Role $role)
    {
        try{

            $role = $this->roleCrudService->destroy($role);

            return (new BaseApiResource($role))->withMessage('Role deleted successfully',200);

        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role deletion failed. Please try again'
            ], 500);
        }
    }

    public function getAssignedUsers(Role $role)
    {
        $users= $this->roleService->getAssignedUsers($role);

        if (is_null($users)) {
            return response()->json([
                'status' => 'failed', 
                'message' => 'No users are assigned to this role.',
                'data' => [],
            ], 204);
        }

        return BaseApiResource::collection($users)->withMessage('Users assigned to this role.');
    }
}