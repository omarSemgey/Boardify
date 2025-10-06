<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MeAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function authenticated_user_can_hit_me()
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
    
        $meResponse = $this->call('GET', '/auth/me', [], $tokens);

        Log::info('Me Response', [$meResponse->getContent()]);

        $meResponse->assertStatus(200);
    
        $meResponse->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'profile',
            ],
            'status',
            'message',
        ]);

        $meResponse->assertJson([
            'status' => 'success',
            'message' => 'User found successfully.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'profile' => $user->profile,
            ],
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_hit_me()
    {
        $meResponse = $this->getJson('/auth/me');

        $meResponse->assertStatus(401);

        $meResponse->assertJsonStructure([
            'message',
        ]);

        $meResponse->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
