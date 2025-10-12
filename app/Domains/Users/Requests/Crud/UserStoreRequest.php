<?php

namespace App\Domains\Users\Requests\Crud;

use App\Domains\Users\DTOs\Crud\UserCreateData;
use App\Domains\Users\Rules\UserRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must NOT be authenticated
        //return auth()->guest(); // returns true if no user is authenticated
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return UserRules::store();
    }

    public function toDTO(): UserCreateData
    {
        return new UserCreateData(
            name: $this->validated('name'),
            email: $this->validated('email'),
            password: $this->validated('password'),
            profile: $this->file('profile')
        );
    }
}
