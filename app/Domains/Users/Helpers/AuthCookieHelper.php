<?php

namespace App\Domains\Users\Helpers;

use Illuminate\Http\JsonResponse;

class AuthCookieHelper
{
    /**
     * Attach authentication cookies to a JSON response.
     */
    public static function attachAuthCookies(JsonResponse $response, string $accessToken, string $refreshToken): JsonResponse
    {
        return $response
            ->cookie(
                'access_token',
                $accessToken,
                (int) config('jwt.ttl'),
                '/',
                null,
                app()->environment('production'),
                true,
                false,
                'Lax'
            )
            ->cookie(
                'refresh_token',
                $refreshToken,
                (int) config('jwt.refresh_ttl'),
                '/',
                null,
                app()->environment('production'),
                true,
                false,
                'Lax'
            );
    }
}
