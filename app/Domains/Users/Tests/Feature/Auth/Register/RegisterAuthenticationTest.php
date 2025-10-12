<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unauthenticated_user_can_hit_register()
    {
        $response = $this->postJson('/auth/register', [
            'name' => 'test',
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
            'message' => 'User created successfully.',
        ]);

        $response->assertCookie('access_token');
        $response->assertCookie('refresh_token');
    }

    #[Test]
    public function authenticated_user_cannot_hit_register()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $loginResponse = $this->postJson('/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $loginResponse->assertStatus(201);

        $registerResponse = $this->postJson('/auth/register', [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => 'secret123',
        ]);

        $registerResponse->assertStatus(403);

        $registerResponse->assertJson([
            'status' => 'failed',
            'message' => 'You are already logged in.',
        ]);
    }
}
