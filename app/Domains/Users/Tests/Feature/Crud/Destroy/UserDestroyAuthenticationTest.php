<?php

namespace App\Domains\Users\Tests\Feature\Crud;

use App\Domains\Users\Traits\Auth\UserAuthTestManager;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserDestroyAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    use UserAuthTestManager;
    
    #[Test]
    public function authenticated_user_can_hit_destroy()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $cookies = $register['cookies'];

        $meResponse = $this->call('DELETE', '/users/destroy', [], $cookies);

        $meResponse->assertStatus(200);
    
        $meResponse->assertJsonStructure([
            'status',
            'message',
        ]);

        $meResponse->assertJson([
            'status' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_hit_destroy()
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
