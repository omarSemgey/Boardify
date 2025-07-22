<?php

namespace App\Services\Users;

use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserCrudService
{
    public function store(array $data)
    {
        try{
            $profileExists = isset($data['profile']);
            DB::beginTransaction();
            if($profileExists){
                $profile = $data['profile'];
                $extension = $profile->getClientOriginalExtension();
                $filename = Str::random(32) . '.' . $extension;
                
                $path = $profile->storeAs('userProfiles', $filename, 'public');
            }
                
                $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'profile' => $profileExists ? Storage::disk('public')->url($path) : null,
            ]);

            DB::commit();
            return $user;
        }catch (\Throwable $err) {
            DB::rollBack();
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            Log::error('User creation failed', ['error' => $err]);
            throw new \Exception('User creation failed. Please try again later');
        }
    }

    public function update(array $data,User $user)
    {
        try {
            $updateData = collect($data)->except('profile','passwords')->toArray();
            $newData=[];

            if( !empty($data['password']) && !Hash::check($data['password'],$user->password)){
                $newData['password'] = Hash::make($data['password']);
            }

            $profileExists = isset($data['profile']) && $data['profile'] instanceof \Illuminate\Http\UploadedFile;
            if($profileExists){
                $profile = $data['profile'];
                $extension = $profile->getClientOriginalExtension();
                $filename = Str::random(32) . '.' . $extension;
                
                $path = $profile->storeAs('userProfiles', $filename, 'public');

                $newData['profile'] =  Storage::disk('public')->url($path);
                Storage::delete($user->profile);
            }

            foreach ($updateData as $key => $value) {
                if ($user->$key !== $value) {
                    $user->$key = $value;
                }
            }

            if ($user->isDirty() || !empty($newData)) {
                $user->fill($newData)->save();
            }

            return $user;
        } catch (\Throwable $err) {
            Log::error('User update failed', ['error' => $err]);
            throw new \Exception('User update failed. Please try again later');
        }
    }
 
    public function destroy(User $user)
    {
        try{

            $user->delete();

            return $user;

        } catch (\Throwable $err) {
            Log::error('User deletion failed', ['error' => $err]);
            throw new \Exception('User deletion failed. Please try again');
        }
    }
}