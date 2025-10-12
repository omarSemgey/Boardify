<?php

namespace App\Domains\Users\Requests\Crud;

use App\Domains\Users\DTOs\Crud\UserUpdateData;
use App\Domains\Users\Rules\UserRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $user = $this->route('user');
        // return $this->user()->can('manageSelf', $user);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return UserRules::update($this->user()->id);
    }

    public function toDTO(): UserUpdateData
    {
        return new UserUpdateData(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password'),
            profile: $this->file('profile'),
        );
    }
}
