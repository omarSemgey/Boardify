<?php

namespace App\Domains\Users\Requests\Auth;

use App\Domains\Users\DTOs\Auth\UserLoginData;
use App\Domains\Users\Rules\UserRules;
use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return UserRules::login();
    } 

        public function toDTO(): UserLoginData
    {
        return new UserLoginData(
            email: $this->validated('email'),
            password: $this->validated('password'),
        );
    }
}
