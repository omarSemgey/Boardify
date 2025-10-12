<?php

namespace App\Domains\Users\Tests\Feature\Crud;

use App\Domains\Users\Models\User;
use App\Domains\Users\Traits\Auth\UserAuthTestManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserUpdateCrudTest extends TestCase
{
    use RefreshDatabase;
    use UserAuthTestManager;

    protected User $user;
    protected $cookies;

    protected function setUp(): void
    {
        parent::setUp(); 

        $register = $this->RegisterUser([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $this->cookies = $register['cookies'];
    }

    #[Test]
    public function user_can_update_all_field()
    {
        $response = $this->postJson( '/users/update', [
            'name' => 'new test',
            'email' => 'new.test@gmail.com',
            'password' => 'newsecret123'
        ],$this->cookies);
        
        $response->assertStatus(200);
        
        $response->assertJson([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' =>[
                'email' => 'new.test@gmail.com'
            ]
        ]);
    }

    #[Test]
    public function user_can_update_name_field()
    {
        $response = $this->postJson( '/users/update', ['name' => 'new test'],$this->cookies);
        
        $response->assertStatus(200);

        Log::info('Response data', ['response' => $response->json()]);
        
        $response->assertJson([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' =>[
                'name' => 'new test'
            ]
        ]);
    }

    #[Test]
    public function user_can_update_email_field()
    {
        $response = $this->postJson( '/users/update', ['email' => 'new.test@gmail.com'],$this->cookies);
        
        $response->assertStatus(200);
        
        $response->assertJson([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' =>[
                'email' => 'new.test@gmail.com'
            ]
        ]);
    }

    #[Test]
    public function user_can_update_password_field()
    {
        $response = $this->postJson( '/users/update', ['password' => 'newsecret123'],$this->cookies);
        
        $response->assertStatus(200);
        
        $response->assertJson([
            'status' => 'success',
            'message' => 'User updated successfully.',
        ]);
    }

    #[Test]
    public function user_can_update_profile_field()
    {
        $profile = UploadedFile::fake()->image('avatar.png');
        $response = $this->postJson( '/users/update', ['profile' => $profile],$this->cookies);

        Log::info('Response data', ['response' => $response->json()]);  
        
        $response->assertStatus(200);
        
        $response->assertJson([
            'status' => 'success',
            'message' => 'User updated successfully.',
        ]);
    }
}
