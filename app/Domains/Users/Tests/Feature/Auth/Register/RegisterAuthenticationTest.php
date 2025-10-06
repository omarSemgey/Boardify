<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Helpers\AuthTokenManager;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserAuthService;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_cannot_hit_register()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $firstResponse = $this->postJson('/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $firstResponse->assertStatus(201);

        $secondResponse = $this->postJson('/auth/register', [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => 'secret123',
        ]);

        $secondResponse->assertStatus(403);

        $secondResponse->assertJson([
            'status' => 'failed',
            'message' => 'You are already logged in.',
        ]);
    }
}
