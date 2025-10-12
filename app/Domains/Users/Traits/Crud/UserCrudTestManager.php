<?php

namespace App\Domains\Users\Tests\Traits\Crud;

use Illuminate\Testing\TestResponse;

trait UserCrudTestManager
{
    protected function registerUser(array $credentials): TestResponse
    {
        return $this->postJson('/auth/register', $credentials);
    }

    protected function loginUser(array $credentials): TestResponse
    {
        return $this->postJson('/auth/login', $credentials);
    }

    protected function LogoutUser(array $cookies): TestResponse
    {
        return $this->call('POST', '/auth/logout', [], $cookies);
    }
}
