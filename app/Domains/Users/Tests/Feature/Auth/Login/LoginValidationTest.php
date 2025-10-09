<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginValidationTest extends TestCase
{
    use RefreshDatabase;


    // Email

    #[Test]
    public function user_cannot_login_with_missing_email()
    {
        $response = $this->postJson('/auth/login', [
            'name' => 'test',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The email field is required.',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function user_cannot_login_with_invalid_email_format()
    {
        $response = $this->postJson('/auth/login', [
            'name' => 'test',
            'email' => 'testgmail.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The email field must be a valid email address.',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }
 
    #[Test]
    public function user_cannot_login_with_long_email()
    {
        $response = $this->postJson('/auth/login', [
            'name' => 'omar',
            'email' => 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@gmail.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The email field must not be greater than 320 characters.',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function user_cannot_login_with_non_string_email()
    {
        $response = $this->postJson('/auth/login', [
            'name' => 'test',
            'email' => ['test@gmail.com'],
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The email field must be a string.',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    // Password

    #[Test]
    public function user_cannot_login_with_missing_password()
    {
        $response = $this->postJson('/auth/login', [
            'name' => 'test',
            'email' => 'test@gmail.com',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The password field is required.',
        ]);

        $response->assertJsonValidationErrors(['password']);
    }

    #[Test]
    public function user_cannot_login_with_short_password()
    {
        $response = $this->postJson('/auth/login', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'sec',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The password field must be at least 6 characters.',
        ]);

        $response->assertJsonValidationErrors(['password']);
    }

}
