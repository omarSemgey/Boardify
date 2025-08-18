<?php

namespace App\Roles\Services;

use App\Exceptions\ApiException;
use App\Roles\Models\Role;
use Illuminate\Support\Facades\DB;

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
            throw new ApiException('Role creation failed. Please try again later.', 500, $err);
        }
    }

    public function update(array $data, Role $role)
    {
        try {
            DB::beginTransaction();

            $updateData = collect($data)->except('permissions')->toArray();

            $role->fill($updateData);

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
            throw new ApiException('Role update failed. Please try again later.', 500, $err);
        }
    }

    public function destroy(Role $role)
    {
        try{

            $role->delete();

            return $role;

        } catch (\Throwable $err) {
            throw new ApiException('Role deletion failed. Please try again later.', 500, $err);
        }
    }
}