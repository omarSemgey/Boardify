<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RefreshLifecycleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function old_access_token_is_invalid_after_refresh()
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
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], $tokens);

        $refreshResponse->assertStatus(200);
    
        $refreshResponse->assertJson([
            'message' => 'Token refreshed successfully.',
        ]);

        $meResponse = $this->call('GET', '/auth/me', [], $tokens);

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
    
        $firstRefreshResponse = $this->call('POST', '/auth/refresh', [], $tokens);

        $firstRefreshResponse->assertStatus(200);

        Log::info('First Refresh Response: ' ,[$firstRefreshResponse->getContent()]);

        $firstRefreshResponse->assertJsonStructure([
            'status',
            'message',
        ]);
    
        $firstRefreshResponse->assertJson([
            'status' => 'success',
            'message' => 'Token refreshed successfully.',
        ]);

        $firstRefreshResponse->assertCookie('access_token');
        $firstRefreshResponse->assertCookie('refresh_token');

        $secondRefreshResponse = $this->call('POST', '/auth/refresh', [], $tokens);

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
        
    
        $firstCookieJar = $loginResponse->headers->getCookies();
        
        $firstTokens = [];

        foreach ($firstCookieJar as $cookie) {
            $firstTokens[$cookie->getName()] = $cookie->getValue();
        }
    
        $refreshResponse = $this->call('POST', '/auth/refresh', [], $firstTokens);

        $refreshResponse->assertStatus(200);
    
        $refreshResponse->assertJson([
            'message' => 'Token refreshed successfully.',
        ]);

        $refreshResponse->assertCookie('access_token');
        $refreshResponse->assertCookie('refresh_token');

        $secondCookieJar = $refreshResponse->headers->getCookies();

        $SecondTokens = [];

        foreach ($secondCookieJar as $cookie) {
            $SecondTokens[$cookie->getName()] = $cookie->getValue();
        }

        $meResponse = $this->call('GET', '/auth/me', [], $SecondTokens);

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
