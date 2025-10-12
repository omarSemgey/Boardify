<?php

namespace App\Domains\Users\Repositories\Crud;

use App\Domains\Users\Contracts\Repositories\Crud\UserCrudRepositoryInterface;
use App\Domains\Users\DTOs\Crud\UserCreateData;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Log;

class UserCrudRepository implements UserCrudRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->fill($data)->save();
    }

    public function delete(User $user): bool
    {
        if ($user->profile) {
            Storage::delete($user->profile);
        }
        return $user->delete();
    }
}
