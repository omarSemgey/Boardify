<?php

namespace App\Domains\Users\Services;

use App\Domains\Users\DTOs\Auth\UserLoginData;
use App\Domains\Users\DTOs\Crud\UserCreateData;
use App\Domains\Users\Helpers\Auth\AuthTokenManager;
use Illuminate\Support\Facades\Auth;
use App\Domains\Users\Services\UserCrudService;
use App\GlobalExceptions\ApiException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthService
{
    protected UserCrudService $userCrudService;

    public function __construct(UserCrudService $userCrudService) {
        $this->userCrudService = $userCrudService;
    }

    public function login(UserLoginData $credentials):array
    {
        try{         
            $accessToken = AuthTokenManager::createAccessToken($credentials->toAuthArray());

            if(!$accessToken){
                throw new ApiException('Invalid email or password.', 401);
            }

            $user = Auth::guard('api')->user();
            
            $refreshToken = AuthTokenManager::createRefreshToken($user);
    
            return [
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ];

        } catch (ApiException $err) {
            throw $err;
        } catch (\Throwable $err) {
            throw new ApiException('User login failed. Please try again later.', 500, $err);
        }
    }

    public function me():object
    {
        try {
            $accessToken = request()->cookie('access_token'); 
            
            $user = JWTAuth::setToken($accessToken)->authenticate();

            if (!$user) {
                throw new ApiException('User not found.', 404);
            }

            return $user;
        } catch (\Exception $err) {
            throw new ApiException('User not found.', 404, $err);
        }    
    }

    public function register(UserCreateData $credentials):array
    {
        try {
            $createdUser = $this->userCrudService->store($credentials);

            $accessToken = AuthTokenManager::createAccessToken($credentials->toAuthArray());
            $refreshToken = AuthTokenManager::createRefreshToken($createdUser);

            return [
                'user' => $createdUser,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ];

        }  catch (\Throwable $err) {
            throw new ApiException('User registration failed. Please try again later.', 500, $err);
        }
    }

    public function logout():object
    {
        try {
            $refreshToken = request()->cookie('refresh_token');
            $accessToken = request()->cookie('access_token');

            if (!$accessToken) {
                throw new ApiException('No access token provided', 401);
            }

            $user = JWTAuth::setToken($accessToken)->authenticate();

            AuthTokenManager::invalidateTokens($accessToken,$refreshToken);

            return $user;
        } catch (\Throwable $err) {
            throw new ApiException('logout failed. Please try again later.', 500, $err);
        }
    }

    public function refresh():array
    {
        try {
            $oldRefreshToken = request()->cookie('refresh_token');
            $oldAccessToken = request()->cookie('access_token');

            $user = JWTAuth::setToken($oldRefreshToken)->authenticate();

            $newAccessToken = JWTAuth::customClaims(['type' => 'access'])->fromUser($user);

            $newRefreshToken = AuthTokenManager::createRefreshToken($user);

            AuthTokenManager::invalidateTokens($oldAccessToken,$oldRefreshToken);

            return [
                'user' => $user,
                'access_token' => $newAccessToken,
                'refresh_token' => $newRefreshToken,
            ];
        } catch (\Throwable $err) {
            throw new ApiException('Token refresh failed. Please try again later.', 500, $err);
        }
    }
}