<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use App\Domains\Users\Traits\Auth\UserAuthTestManager;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RefreshAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    use UserAuthTestManager;
    
    #[Test]
    public function authenticated_user_can_hit_refresh()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $cookies = $register['cookies'];
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], $cookies);

        $refreshResponse->assertStatus(200);
    
        $refreshResponse->assertJsonStructure([
            'status',
            'message',
        ]);
    
        $refreshResponse->assertJson([
            'status' => 'success',
            'message' => 'Token refreshed successfully.',
        ]);

        $refreshResponse->assertCookie('access_token');
        $refreshResponse->assertCookie('refresh_token');
    }

    #[Test]
    public function unauthenticated_user_cannot_hit_refresh()
    {
        $refreshResponse = $this->postJson('/auth/refresh');

        $refreshResponse->assertStatus(401);

        $refreshResponse->assertJsonStructure([
            'message',
        ]);

        $refreshResponse->assertJson([
            'message' => 'Unauthenticated.',
        ]);

        $refreshResponse->assertCookieMissing('access_token');
        $refreshResponse->assertCookieMissing('refresh_token');
    }

        #[Test]
    public function user_with_invalid_refresh_token_cannot_refresh()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $access_token = $register['cookies']['access_token'];
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], ['access_token' => $access_token, 'refresh_token' => 'some-invalid-token']);

        $refreshResponse->assertStatus(500);

        $refreshResponse->assertJson([
            'message' => 'Token refresh failed. Please try again later.',
        ]);

        $refreshResponse->assertCookieMissing('access_token');
        $refreshResponse->assertCookieMissing('refresh_token');
    }
}
