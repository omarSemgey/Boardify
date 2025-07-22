<?php

if (! function_exists('attachAuthCookies')) {
    function attachAuthCookies($response, $accessToken, $refreshToken) {
        return $response
            ->cookie('access_token', $accessToken, (int) config('jwt.ttl'), '/', null, app()->environment('production'), true, false, 'Lax')
            ->cookie('refresh_token', $refreshToken, (int) config('jwt.refresh_ttl'), '/', null, app()->environment('production'), true, false, 'Lax');
    }
}