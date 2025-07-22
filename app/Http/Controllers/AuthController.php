<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\BaseApiResource;
use App\Services\Users\UserAuthService;

class AuthController extends Controller
{
    protected UserAuthService $userAuthservice;

    public function __construct(UserAuthService $userAuthservice) {
        $this->userAuthservice = $userAuthservice;
    }

    public function login(LoginRequest $request)
    {
        try{
            $result = $this->userAuthservice->login($request->validate());

            $response = (new AuthResource($result['user']))->withMessage('User created successfully',201) ->response();

            return attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
    
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'User login failed. Please try again later.',
            ], 500)
            ->withoutCookie('access_token')
            ->withoutCookie('refresh_token');
        }
    }

    public function me()
    {
        try {
            $user = $this->userAuthservice->me();

            return (new BaseApiResource($user))->withMessage('User found successfully', 200)->additional(['user' => $user]);

        } catch (\Exception $e) {
            return response()
            ->json([
                    'error' => 'invalid_token'
                ], 401)
                ->withoutCookie('access_token')
                ->withoutCookie('refresh_token');
        }    
    }

    public function register(StoreUserRequest $request)
    {
        try {
            $result = $this->userAuthservice->register($request->validated());

            $response =  (new AuthResource($result['user']))->withMessage('User created successfully',201)->response();
            return attachAuthCookies($response, $result['access_token'], $result['refresh_token']);

        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'User registration failed. Please try again later.',
            ], 500)
            ->withoutCookie('access_token')
            ->withoutCookie('refresh_token');
        }
    }

    public function logout()
    {
        try {
            
            $user =  $this->userAuthservice->logout();
            return (new BaseApiResource($user))->withMessage('Logout successfull', 200)->response()
            ->withoutCookie('access_token')
            ->withoutCookie('refresh_token');

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed',
                'error' => $e
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $result =$this->userAuthservice->refresh();

            $response = (new AuthResource($result['user']))->withMessage('Token refreshed successfully',201)->response();
            return attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token refresh failed'
            ], 401)
            ->withoutCookie('access_token')
            ->withoutCookie('refresh_token');
        }
    }
}