<?php

namespace App\Domains\Users\Requests;

use App\Domains\Users\DTOs\Crud\UserUpdateData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');
        return $this->user()->can('manageSelf', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ['name' => 'bail|sometimes|string|min:3|max:30|regex:/^[\p{L}0-9_\s]{3,30}$/u', Rule::unique('users')->ignore($this->user)],
            ['email' => 'bail|sometimes|string|email:rfc,dns|max:320',  Rule::unique('users')->ignore($this->user)],
            'password' => 'bail|sometimes|string|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d).{6,}$/',
            'profile' =>  [
                'bail',
                'sometimes',
                'image',
                'mimes:png,jpg,jpeg',
                'max:4096',
                Rule::dimensions()->maxWidth(2000)->maxHeight(2000),
                function ($attribute, $value, $fail) {
                    if ($value && preg_match('/\.[^.]+\./', $value->getClientOriginalName())) {
                        $fail('Invalid image file name detected.');
                    }
                }
            ],
        ];
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
