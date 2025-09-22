<?php

namespace App\Domains\Users\Services;
use Illuminate\Support\Facades\Auth;
use App\Domains\Users\Services\UserCrudService;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthService
{
    protected UserCrudService $userCrudService;

    public function __construct(UserCrudService $userCrudService) {
        $this->userCrudService = $userCrudService;
    }

    public function login(array $data):array
    {
        try{         
            $accessToken = Auth::guard('api')->attempt($data);
    
            if (!$accessToken) {
                Log::error('Unauthorized.',);
                throw new \Exception('Unauthorized');
            }
    
            $user = Auth::guard('api')->user();
            $refreshToken = JWTAuth::fromUser($user);
    
            return [
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ];

        } catch (\Throwable $err) {
            Log::error('Login failed', ['error' => $err]);
            throw new \Exception('Login failed. Please try again later');
        }
    }

    public function me():object
    {
        try {
            $accessToken = request()->cookie('access_token'); 
            
            if (!$accessToken) {
                Log::error('Unauthorized.',);
                throw new \Exception('Unauthorized');
            }

            $user = JWTAuth::setToken($accessToken)->authenticate();

            if (!$user) {
                Log::error('User not found.',);
               throw new \Exception('User not found');
            }

            return $user;
        } catch (\Exception $err) {
            Log::error('User not found.', ['error' => $err]);
            throw new \Exception('User not found. Please try again later');
        }    
    }

    public function register(array $data):array
    {
        try {
            $createdUser = $this->userCrudService->store($data);

            $accessToken = Auth::guard('api')->login($createdUser);
            $refreshToken = JWTAuth::fromUser($createdUser);

            return [
                'user' => $createdUser,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ];

        }  catch (\Throwable $err) {
            Log::error('User registration failed', ['error' => $err]);
            throw new \Exception(message: 'User registration failed. Please try again later');
        }
    }

    public function logout():object
    {
        try {
            $refreshToken = request()->cookie('refresh_token');
            $accessToken = request()->cookie('access_token');

            $user = JWTAuth::setToken($accessToken)->authenticate();
            
            if (!$refreshToken) {
                throw new \Exception('No refresh token');
            }

            if (!$user) {
                Log::error('User not found.');
                throw new \Exception('User not found');
            }

            JWTAuth::invalidate($refreshToken);
            JWTAuth::invalidate($accessToken);

            return $user;
        } catch (\Throwable $err) {
            Log::error('logout failed', ['error' => $err]);
            throw new \Exception('logout failed. Please try again later');
        }
    }

    public function refresh():array
    {
        try {
            $oldRefreshToken = request()->cookie('refresh_token');

            if (!$oldRefreshToken) {
                throw new \Exception('No refresh token');
            }

            $user = JWTAuth::setToken($oldRefreshToken)->authenticate();

            $newAccessToken = auth('api')->login($user);

            $newRefreshToken = JWTAuth::fromUser($user);

            JWTAuth::invalidate($oldRefreshToken);

            return [
                'user' => $user,
                'access_token' => $newAccessToken,
                'refresh_token' => $newRefreshToken,
            ];

        } catch (\Throwable $err) {
            Log::error('User registration failed', ['error' => $err]);
            throw new \Exception('User registration failed. Please try again later');
        }
    }
}