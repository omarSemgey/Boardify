<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\Requests\LoginRequest;
use App\Domains\Users\Requests\StoreUserRequest;

use App\Domains\Users\Helpers\AuthTokenManager;
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
        $credentials = $request->validated();
        $result = $this->userAuthservice->login($credentials);

        $response = (new AuthResource($result['user']))->withMessage('User logged in successfully.',201)->response();

        return AuthTokenManager::attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
    }

    public function me()
    {
        $user = $this->userAuthservice->me();

        return (new BaseApiResource($user))->withMessage('User found successfully.', 200)->additional(['user' => $user]);
    }    

    public function register(StoreUserRequest $request)
    {
        $result = $this->userAuthservice->register($request->validated());

        $response =  (new AuthResource($result['user']))->withMessage('User created successfully.',201)->response();
        return AuthTokenManager::attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
    }

    public function logout()
    {
        $user =  $this->userAuthservice->logout();
        return (new BaseApiResource($user))->withMessage('Logout successfull.', 200)->response();
    }

    public function refresh()
    {
        $result =$this->userAuthservice->refresh();

        $response = (new BaseApiResource(null))->withMessage('Token refreshed successfully.', 200)->response();

        return AuthTokenManager::attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
    }
}