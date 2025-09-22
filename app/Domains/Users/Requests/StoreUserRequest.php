<?php

namespace App\Domains\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must NOT be authenticated
        return auth()->guest(); // returns true if no user is authenticated
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string|min:3|max:30|regex:/^[\p{L}0-9_\s]{3,30}$/u|unique:users',
            'email' => 'bail|required|string|email:rfc,dns|max:320|unique:users',,
            'password' => 'bail|required|string|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d).{6,}$/',
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
}
