<?php

namespace App\Domains\Users\Helpers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTokenManager
{
    public static function createAccessToken(array $credentials): string
    {
        return Auth::guard('api')->attempt($credentials);
    }

    public static function createRefreshToken($user): string
    {
        return JWTAuth::customClaims(['type'=>'refresh'])->fromUser($user);
    }

    public static function invalidateTokens(?string $accessToken = null, ?string $refreshToken = null): void {
        if ($accessToken) JWTAuth::setToken($accessToken)->invalidate();
        if ($refreshToken) JWTAuth::setToken($refreshToken)->invalidate();
    }

    public static function attachAuthCookies($response, string $accessToken, string $refreshToken): JsonResponse
    {
        return $response
            ->cookie('access_token', $accessToken, (int) config('jwt.ttl'), '/', null, app()->environment('production'), true, false, 'Lax')
            ->cookie('refresh_token', $refreshToken, (int) config('jwt.refresh_ttl'), '/', null, app()->environment('production'), true, false, 'Lax');
    }
}

