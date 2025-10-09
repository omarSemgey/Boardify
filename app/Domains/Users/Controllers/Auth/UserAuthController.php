<?php

namespace App\Domains\Users\Controllers\Auth;

use App\Domains\Users\Requests\Auth\UserLoginRequest;
use App\Domains\Users\Requests\Crud\UserStoreRequest;

use App\Domains\Users\Helpers\Auth\AuthTokenManager;
use App\GlobalApiFormatters\BaseApiResource;
use App\GlobalApiFormatters\AuthResource;

use App\Domains\Users\Services\Auth\UserAuthService;

use App\Http\Controllers\Controller;

class UserAuthController extends Controller
{
    protected UserAuthService $userAuthservice;

    public function __construct(UserAuthService $userAuthservice) {
        $this->userAuthservice = $userAuthservice;
    }

    public function login(UserLoginRequest $request)
    {
        $dto = $request->toDTO();
        $result = $this->userAuthservice->login($dto);

        $response = (new AuthResource($result['user']))->withMessage('User logged in successfully.',201)->response();

        return AuthTokenManager::attachAuthCookies($response, $result['access_token'], $result['refresh_token']);
    }

    public function me()
    {
        $user = $this->userAuthservice->me();

        return (new BaseApiResource($user))->withMessage('User found successfully.', 200)->additional(['user' => $user]);
    }    

    public function register(UserStoreRequest $request)
    {
        $dto = $request->toDTO();
        $result = $this->userAuthservice->register($dto);

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