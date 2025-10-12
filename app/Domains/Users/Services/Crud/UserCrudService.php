<?php

namespace App\Domains\Users\Services\Crud;

use App\Domains\Users\Contracts\Repositories\Crud\UserCrudRepositoryInterface;
use App\Domains\Users\DTOs\Crud\UserCreateData;
use App\Domains\Users\DTOs\Crud\UserUpdateData;
use App\Domains\Users\Helpers\Logic\ProfileUploader;
use App\GlobalExceptions\ApiException;
use App\Domains\Users\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserCrudService
{
    protected UserCrudRepositoryInterface $userRepository;

    public function __construct(UserCrudRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(UserCreateData $data)
    {
        DB::beginTransaction();
        try{
            $userData = [
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'profile' => null,
            ];
    
            if($data->profile instanceof \Illuminate\Http\UploadedFile){
                $path = ProfileUploader::upload($data->profile);
                $userData['profile'] = Storage::disk('public')->url($path);
            }

            $user = $this->userRepository->create($userData);

            DB::commit();
            return $user;
        }catch (\Throwable $err) {
            DB::rollBack();
            if (isset($path) & Storage::disk('public')->exists($path)) {
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
                $path = ProfileUploader::upload($data->profile);

                $newData['profile'] =  Storage::disk('public')->url($path);
                if(isset($user->profile)) Storage::delete($user->profile);
            }

            $user->fill(array_merge($updateData, $newData));

            if ($user->isDirty() || !empty($newData)) {
                $this->userRepository->update($user, $newData);
            }

            return $user;
        } catch (\Throwable $err) {
            throw new ApiException('User update failed. Please try again later.', 500, $err);
        }
    }
 
    public function destroy(User $user)
    {
        try{

            $this->userRepository->delete($user);

        } catch (\Throwable $err) {
            throw new ApiException('User deletion failed. Please try again later.', 500, $err);
        }
    }
}