<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);
        
        $response = $this->postJson('/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);
        
        $response->assertStatus(201);
        
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'profile',
                ],
                'authorization' => [
                    'type',
                    'expires_in',
                ],
            ],
            'status',
            'message',
        ]);

        $response->assertJson([
            'status' => 'success',
            'message' => 'User logged in successfully.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile' => $user->profile,
                ],
            ],
        ]);
        
        $response->assertCookie('access_token');
        $response->assertCookie('refresh_token');
    }
}
