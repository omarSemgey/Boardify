<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use App\Domains\Users\Traits\Auth\UserAuthTestManager;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RefreshLifecycleTest extends TestCase
{
    use RefreshDatabase;
    use UserAuthTestManager;

    #[Test]
    public function old_access_token_is_invalid_after_refresh()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $cookies = $register['cookies'];
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], $cookies);

        $refreshResponse->assertStatus(200);
    
        $refreshResponse->assertJson([
            'message' => 'Token refreshed successfully.',
        ]);

        $meResponse = $this->call('GET', '/auth/me', [], $cookies);

        $meResponse->assertStatus(404);

        $meResponse->assertJsonStructure([
            'status',
            'message',
            'errors'
        ]);

        $meResponse->assertJson([
            'status' => 'failed',
            'message' => 'User not found.',
            'errors' => 'The token has been blacklisted',
        ]);
    }

    #[Test]
    public function old_refresh_token_cannot_be_reused_after_refresh()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $cookies = $register['cookies'];
    
        $firstRefreshResponse = $this->call('POST', '/auth/refresh', [], $cookies);

        $firstRefreshResponse->assertStatus(200);

        $firstRefreshResponse->assertCookie('access_token');
        $firstRefreshResponse->assertCookie('refresh_token');

        $secondRefreshResponse = $this->call('POST', '/auth/refresh', [], $cookies);

        $secondRefreshResponse->assertStatus(500);

        $secondRefreshResponse->assertJsonStructure([
            'status',
            'message',
            'errors',
        ]);

        $secondRefreshResponse->assertJson([
            'status' => 'failed',
            'message' => 'Token refresh failed. Please try again later.',
            'errors' => 'The token has been blacklisted'
        ]);
    }

    #[Test]
    public function refreshed_access_token_grants_access()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $cookies = $register['cookies'];

        $firstCookies = $register['cookies'];
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], $firstCookies);

        $refreshResponse->assertStatus(200);
    
        $refreshResponse->assertJson([
            'message' => 'Token refreshed successfully.',
        ]);

        $refreshResponse->assertCookie('access_token');
        $refreshResponse->assertCookie('refresh_token');

        $secondCookies = $this->extractCookies($refreshResponse);

        $meResponse = $this->call('GET', '/auth/me', [], $secondCookies);

        $meResponse->assertStatus(200);

        $meResponse->assertJsonStructure([
            'status',
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'profile',
            ],
        ]);

        $meResponse->assertJson([
            'message' => 'User found successfully.',
            'status' => 'success',
        ]);
    }
}
