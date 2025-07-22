<?php

namespace App\Services\Roles;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleCrudService
{
    public function store(array $data)
    {
        try{
            $role = Role::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'type_id' => $data['type_id'],
                'board_id' => $data['board_id'],
            ]);

            $role->permissions()->sync($data['permissions']);

            return $role->load('permissions');
        }catch (\Throwable $err) {
            Log::error('Role creation failed', ['error' => $err]);
            throw new \Exception('Role creation failed. Please try again later');
        }
    }

    public function update(array $data,Role $role)
    {
        try {
            DB::beginTransaction();

            $updateData = collect($data)->except('permissions')->toArray();

            foreach ($updateData as $key => $value) {
                if ($role->$key !== $value) {
                    $role->$key = $value;
                }
            }

            if ($role->isDirty()) {
                $role->save();
            }
            
            if (isset($data['permissions'])) {
                $role->permissions()->sync($data['permissions']);
            }

            DB::commit();

            return $role->load('permissions');

        } catch (\Throwable $err) {
            DB::rollBack();
            Log::error('Role update failed', ['error' => $err]);
            throw new \Exception('Role creation failed. Please try again later');
        }
    }
    public function destroy(Role $role)
    {
        try{

            $role->delete();

            return $role;

        } catch (\Throwable $err) {
            Log::error('Role deletion failed', ['error' => $err]);
            throw new \Exception('Role deletion failed. Please try again.');
        }
    }
}