<?php

namespace App\Http\Controllers;

use App\Roles\Requests\StoreRoleRequest;
use App\Roles\Requests\UpdateRoleRequest;
use App\GlobalResources\Resources\BaseApiResource;
use App\Roles\Models\Role;
use App\Roles\Services\RoleCrudService;
use App\Roles\Services\RoleService;
use App\Http\Controllers\Controller;

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
        $createdRole = $this->roleCrudService->store($request->validated());
            
        return new BaseApiResource($createdRole)->withMessage('Role created successfully',201);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $updatedRole = $this->roleCrudService->update($request->validated(),$role);

        return new BaseApiResource($updatedRole)->withMessage('Role updated successfully', 200);
    }

    public function destroy(Role $role)
    {
        $role = $this->roleCrudService->destroy($role);

        return new BaseApiResource($role)->withMessage('Role deleted successfully',200);
    }

    public function getAssignedUsers(Role $role)
    {
        $users= $this->roleService->getAssignedUsers($role);

        return BaseApiResource::collection($users)->withMessage('Users assigned to this role.');
    }
}