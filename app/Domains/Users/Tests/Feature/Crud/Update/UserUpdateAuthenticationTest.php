<?php

namespace App\Domains\Users\Tests\Feature\Crud;

use App\Domains\Users\Traits\Auth\UserAuthTestManager;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserUpdateAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    use UserAuthTestManager;

    #[Test]
    public function authenticated_user_can_hit_update()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson( '/users/update', [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => 'secret123',
        ],$cookies);
        
        $response->assertStatus(200);
        
        $response->assertJson([
            'status' => 'success',
            'message' => 'User updated successfully.',
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_hit_update()
    {
        $response = $this->postJson('/users/update', [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(401);

        $response->assertJsonStructure([
            'message',
        ]);

        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
