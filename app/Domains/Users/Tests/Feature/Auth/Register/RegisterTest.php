<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Helpers\AuthTokenManager;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserAuthService;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_register_with_valid_credentials()
    {
        $response = $this->postJson('/auth/register', [
            'name' => 'ahmad',
            'email' => 'ahmad@gmail.com',
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
}
