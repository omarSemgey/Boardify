<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Helpers\AuthTokenManager;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserAuthService;
use App\GlobalApiFormatters\AuthResource;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function authenticated_user_can_hit_refresh()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);
        
        $loginResponse = $this->postJson('/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);
        
        $loginResponse->assertStatus(201);

        $loginResponse->assertCookie('access_token');
        $loginResponse->assertCookie('refresh_token');
    
        $cookieJar = $loginResponse->headers->getCookies();
        
        $tokens = [];

        foreach ($cookieJar as $cookie) {
            $tokens[$cookie->getName()] = $cookie->getValue();
        }
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], $tokens);

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
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);
        
        $loginResponse = $this->postJson('/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);
        
        $loginResponse->assertStatus(201);

        $loginResponse->assertCookie('access_token');
        $loginResponse->assertCookie('refresh_token');
    
        $cookieJar = $loginResponse->headers->getCookies();
        
        $tokens = [];

        foreach ($cookieJar as $cookie) {
            if($cookie->getName() === 'refresh_token') {
                $tokens[$cookie->getName()] = 'some-invalid-token';
            }else{
                $tokens[$cookie->getName()] = $cookie->getValue();
            }
        }
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], $tokens);

        $refreshResponse->assertStatus(500);

        $refreshResponse->assertJson([
            'message' => 'Token refresh failed. Please try again later.',
        ]);

        $refreshResponse->assertCookieMissing('access_token');
        $refreshResponse->assertCookieMissing('refresh_token');
    }
}
