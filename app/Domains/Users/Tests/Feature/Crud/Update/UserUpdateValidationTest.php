<?php

namespace App\Domains\Users\Tests\Feature\Crud;

use App\Domains\Users\Models\User;
use App\Domains\Users\Traits\Auth\UserAuthTestManager;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Storage;
use Tests\TestCase;

class UserUpdateValidationTest extends TestCase
{
    use RefreshDatabase;
    use UserAuthTestManager;

    // Name

    #[Test]
    public function user_cannot_update_with_existing_name()
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);
        
        $register = $this->RegisterUser( [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson( '/users/update', ['name' => 'test'],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The name has already been taken.',
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function user_cannot_update_with_non_string_name()
    {
        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['name' => ['test2']],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The name field must be a string.',
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function user_cannot_update_with_short_name()
    {
        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['name' => 'te'],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The name field must be at least 3 characters.',
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function user_cannot_update_with_long_name()
    {
        $register = $this->RegisterUser( [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['name' => 'testtesttesttesttesttesttesttes'],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The name field must not be greater than 30 characters.',
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function user_cannot_update_with_invalid_name_format()
    {
        $register = $this->RegisterUser( [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['name' => 'test@!'],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The name field format is invalid.',
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    // Email

    #[Test]
    public function user_cannot_update_with_existing_email()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);
        $response = $this->postJson('/auth/register', [
            'name' => 'omar',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The email has already been taken.',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function user_cannot_update_with_non_string_email()
    {
        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['email' => ['test@gmail.com']],$cookies);

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

     
    #[Test]
    public function user_cannot_update_with_long_email()
    {
        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['email' => 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@gmail.com'],$cookies);
        
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
    public function user_cannot_update_with_invalid_email_format()
    {
        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['email' => 'testgmail.com'],$cookies);

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

    // Password

    #[Test]
    public function user_cannot_update_with_short_password()
    {
        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson('/users/update', ['password' => 'sec'],$cookies);

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

    // Profile

    #[Test]
    public function user_cannot_update_with_non_image_profile()
    {
        Storage::fake('public');
        $profile = UploadedFile::fake()->create('avatar.pdf');

        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson( '/users/update', ['profile' => $profile],$cookies);
    
        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The profile field must be an image.',
        ]);

        $response->assertJsonValidationErrors(['profile']);
    }

    #[Test]
    public function user_cannot_update_with_invalid_mime_type_profile()
    {
        $profile = UploadedFile::fake()->create('avatar.gif',);
        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson( '/users/update', ['profile' => $profile],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The profile field must be a file of type: png, jpg, jpeg.',
        ]);

        $response->assertJsonValidationErrors(['profile']);
    }

    #[Test]
    public function user_cannot_update_with_too_large_profile()
    {
        $profile = UploadedFile::fake()->create('avatar.png')->size(5000);

        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson( '/users/update', ['profile' => $profile],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The profile field must not be greater than 4096 kilobytes.',
        ]);

        $response->assertJsonValidationErrors(['profile']);
    }

    #[Test]
    public function user_cannot_update_with_profile_exceeding_max_dimensions()
    {
        $profile = UploadedFile::fake()->image('avatar.png', 3000 , 3000);

        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson( '/users/update', ['profile' => $profile],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The profile field has invalid image dimensions.',
        ]);
    $response->assertJsonValidationErrors(['profile']);
    }

    #[Test]
    public function user_cannot_update_with_invalid_profile_filename()
    {
        $profile = UploadedFile::fake()->image('avatar.bad.png');

        $register = $this->RegisterUser( [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $cookies = $register['cookies'];

        $response = $this->postJson( '/users/update', ['profile' => $profile],$cookies);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'Invalid image file name detected.',
        ]);
    $response->assertJsonValidationErrors(['profile']);
    }
}
