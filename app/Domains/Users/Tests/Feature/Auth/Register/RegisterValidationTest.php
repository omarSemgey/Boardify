<?php

namespace App\Domains\Users\Tests\Feature\Auth;

use App\Domains\Users\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Storage;
use Tests\TestCase;

class RegisterValidationTest extends TestCase
{
    use RefreshDatabase;

    // Name

   #[Test]
    public function user_cannot_register_with_missing_name()
    {
        $response = $this->postJson('/auth/register', [
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'errors',
            'message',
        ]);

        $response->assertJson([
            'message' => 'The name field is required.',
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function user_cannot_register_with_existing_name()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/auth/register', [
            'name' => 'test',
            'email' => 'test2@gmail.com',
            'password' => 'secret123',
        ]);

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
    public function user_cannot_register_with_non_string_name()
    {
        $response = $this->postJson('/auth/register', [
            'name' => ['test'],
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

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
    public function user_cannot_register_with_short_name()
    {
        $response = $this->postJson('/auth/register', [
            'name' => 'ah',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

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
    public function user_cannot_register_with_long_name()
    {
        $response = $this->postJson('/auth/register', [
            'email' => 'test@gmail.com',
            'name' => 'testtesttesttesttesttesttesttes',
            'password' => 'secret123',
        ]);

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
    public function user_cannot_register_with_invalid_name_format()
    {
        $response = $this->postJson('/auth/register', [
            'name' => 'test@!',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
        ]);

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
    public function user_cannot_register_with_missing_email()
    {
        $response = $this->postJson('/auth/register', [
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
    public function user_cannot_register_with_invalid_email_format()
    {
        $response = $this->postJson('/auth/register', [
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
    public function user_cannot_register_with_existing_email()
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
    public function user_cannot_register_with_long_email()
    {
        $response = $this->postJson('/auth/register', [
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
    public function user_cannot_register_with_non_string_email()
    {
        $response = $this->postJson('/auth/register', [
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
    public function user_cannot_register_with_missing_password()
    {
        $response = $this->postJson('/auth/register', [
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
    public function user_cannot_register_with_short_password()
    {
        $response = $this->postJson('/auth/register', [
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

    // Profile

    #[Test]
    public function user_cannot_register_with_non_avatar_profile()
    {
        Storage::fake('public');
        $profile = UploadedFile::fake()->create('avatar.pdf');
    
        $response = $this->postJson('/auth/register', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
            'profile' => $profile,
        ]);

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
    public function user_cannot_register_with_invalid_mime_type_profile()
    {
    $profile = UploadedFile::fake()->create('avatar.gif',);
        $response = $this->postJson('/auth/register', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
            'profile' => $profile,
        ]);

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
    public function user_cannot_register_with_too_large_profile()
    {
        $profile = UploadedFile::fake()->create('avatar.png')->size(5000);
        $response = $this->postJson('/auth/register', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
            'profile' => $profile,
        ]);

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
    public function user_cannot_register_with_profile_exceeding_max_dimensions()
    {
        $profile = UploadedFile::fake()->image('avatar.png', 3000 , 3000);
        $response = $this->postJson('/auth/register', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
            'profile' => $profile,
        ]);

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
    public function user_cannot_register_with_invalid_profile_filename()
    {
        $profile = UploadedFile::fake()->image('avatar.bad.png');
        $response = $this->postJson('/auth/register', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret123',
            'profile' => $profile,
        ]);

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
