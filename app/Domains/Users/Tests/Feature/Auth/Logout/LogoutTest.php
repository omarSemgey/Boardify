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

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_hit_logout()
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
    
        $cookieJar = $loginResponse->headers->getCookies();
        
        $tokens = [];

        foreach ($cookieJar as $cookie) {
            $tokens[$cookie->getName()] = $cookie->getValue();
        }
    
        $accessToken = $tokens['access_token'];
        $refreshToken = $tokens['refresh_token'];
        Log::info("Access Token: " . $accessToken);

        $logoutResponse = $this->call('POST', '/auth/logout', [], $tokens);

        $logoutResponse->assertStatus(200);
    
        $logoutResponse->assertJson([
            'message' => 'Logout successfull.',
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_hit_logout()
    {
        $logoutResponse = $this->postJson('/auth/logout');

        $logoutResponse->assertStatus(401);

        $logoutResponse->assertJsonStructure([
            'message',
        ]);

        $logoutResponse->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
