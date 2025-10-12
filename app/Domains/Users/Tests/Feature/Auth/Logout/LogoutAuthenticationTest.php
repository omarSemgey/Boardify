<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use App\Domains\Users\Traits\Auth\UserAuthTestManager;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogoutAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    use UserAuthTestManager;

    #[Test]
    public function authenticated_user_can_hit_logout()
    {
        $register = $this->registerUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $cookies = $register['cookies'];
        
        $logoutResponse = $this->call('POST', '/auth/logout', [], $cookies);

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
