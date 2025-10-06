<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_cannot_hit_login()
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

        $secondResponse = $this->postJson('/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $secondResponse->assertStatus(403);

        $secondResponse->assertJsonStructure([
            'status',
            'message',
        ]);

        $secondResponse->assertJson([
            'status' => 'failed',
            'message' => 'You are already logged in.',
        ]);
    }

    #[Test]
    public function user_cannot_login_with_non_existent_email()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'test2@gmail.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(401);

        $response->assertJsonStructure([
            'status',
            'message',
        ]);

        $response->assertJson([
            'status' => 'failed',
            'message' => 'Invalid email or password.',
        ]);
    }

    #[Test]
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'test@gmail.com',
            'password' => '123secret',
        ]);

        $response->assertStatus(401);

        $response->assertJsonStructure([
            'status',
            'message',
        ]);

        $response->assertJson([
            'status' => 'failed',
            'message' => 'Invalid email or password.',
        ]);
    }

}
