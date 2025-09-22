<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\Requests\LoginRequest;
use App\Domains\Users\Requests\StoreUserRequest;

use App\Domains\Users\Helpers\AuthCookieHelper;

use App\GlobalApiFormatters\BaseApiResource;
use App\GlobalApiFormatters\AuthResource;

use App\Domains\Users\Services\UserAuthService;

use App\Http\Controllers\Controller;

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

            return AuthCookieHelper::attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
    
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
            return AuthCookieHelper::attachAuthCookies($response, $result['access_token'], $result['refresh_token']);

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
            return AuthCookieHelper::attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
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