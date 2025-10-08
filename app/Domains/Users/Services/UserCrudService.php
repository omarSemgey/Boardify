<?php

namespace App\Domains\Users\Services;

use App\Domains\Users\DTOs\Crud\UserCreateData;
use App\Domains\Users\DTOs\Crud\UserUpdateData;
use App\GlobalExceptions\ApiException;
use App\Domains\Users\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserCrudService
{
    public function store(UserCreateData $data)
    {
        try{
            $profileExists = isset($data['profile']);
            DB::beginTransaction();
            if($profileExists){
                $profile = $data->profile;
                $extension = $profile->getClientOriginalExtension();
                $filename = Str::random(32) . '.' . $extension;
                
                $path = $profile->storeAs('userProfiles', $filename, 'public');
            }
                
                $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'profile' => $profileExists ? Storage::disk('public')->url($path) : null,
            ]);

            DB::commit();
            return $user;
        }catch (\Throwable $err) {
            DB::rollBack();
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            throw new ApiException('User creation failed. Please try again later.', 500, $err);
        }
    }

    public function update(UserUpdateData $data,User $user)
    {
        try {
            $updateData = $data->toArray();
            $newData=[];

            if( !empty($data->password) && !Hash::check($data->password,$user->password)){
                $newData['password'] = Hash::make($data->password);
            }

            $profileExists = isset($data->profile) && $data->profile instanceof \Illuminate\Http\UploadedFile;
            if($profileExists){
                $profile = $data->profile;
                $extension = $profile->getClientOriginalExtension();
                $filename = Str::random(32) . '.' . $extension;
                
                $path = $profile->storeAs('userProfiles', $filename, 'public');

                $newData['profile'] =  Storage::disk('public')->url($path);
                Storage::delete($user->profile);
            }

            $user->fill(array_merge($updateData, $newData));

            if ($user->isDirty() || !empty($newData)) {
                $user->fill($newData)->save();
            }

            return $user;
        } catch (\Throwable $err) {
            throw new ApiException('User update failed. Please try again later.', 500, $err);
        }
    }
 
    public function destroy(User $user)
    {
        try{

            $user->delete();

            return $user;

        } catch (\Throwable $err) {
            throw new ApiException('User deletion failed. Please try again later.', 500, $err);
        }
    }
}