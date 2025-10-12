<?php

namespace App\Domains\Users\Traits\Auth;

use Illuminate\Testing\TestResponse;

trait UserAuthTestManager
{
    protected function registerUser(array $credentials): array
    {
        $response =  $this->postJson('/auth/register', $credentials);

        $response->assertStatus(201);

        $response->assertCookie('access_token');
        $response->assertCookie('refresh_token');

        $cookies = $this->extractCookies($response);

        return [
            'response' => $response,
            'cookies' => $cookies
        ];
    }

    protected function loginUser(array $credentials): array
    {
        $response = $this->postJson('/auth/login', $credentials);

        $response->assertStatus(201);

        $response->assertCookie('access_token');
        $response->assertCookie('refresh_token');

        $cookies = $this->extractCookies($response);

        return [
            'response' => $response,
            'cookies' => $cookies
        ];
    }

    protected function extractCookies(TestResponse $response): array
    {
        $cookieJar = $response->headers->getCookies();
        
        $cookies = [];

        foreach ($cookieJar as $cookie) {
            $cookies[$cookie->getName()] = $cookie->getValue();
        }

        return $cookies;
    }

    protected function LogoutUser(array $cookies): TestResponse
    {
        return $this->call('POST', '/auth/logout', [], $cookies);
    }
}
