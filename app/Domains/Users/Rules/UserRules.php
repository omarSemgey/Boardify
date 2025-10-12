<?php

namespace App\Domains\Users\Rules;

use Illuminate\Validation\Rule;

class UserRules
{
    public static function base(): array
    {
        return [
            'name' => ['bail','string','min:3','max:30','regex:/^[\p{L}0-9_\s]{3,30}$/u'],
            'email' => ['bail','string','email:rfc,dns','max:320'],
            'password' => ['bail','string','min:6','regex:/^(?=.*[A-Za-z])(?=.*\d).{6,}$/'],
            'profile' => [
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

    public static function store(): array
    {
        $rules = self::base();
        $rules['name'][] = 'required';
        $rules['name'][] = Rule::unique('users');
        $rules['email'][] = 'required';
        $rules['email'][] = Rule::unique('users');
        $rules['password'][] = 'required';

        return $rules;
    }

    public static function update($userId): array
    {
        $rules = self::base();
        $rules['name'][] = 'sometimes';
        $rules['name'][] = Rule::unique('users')->ignore($userId);
        $rules['email'][] = 'sometimes';
        $rules['email'][] = Rule::unique('users')->ignore($userId);
        $rules['password'][] = 'sometimes';

        return $rules;
    }

    public static function login(): array
    {
        $base = self::base();

        return [
            'email' => array_merge(['required'], $base['email']),
            'password' => array_merge(['required'], $base['password']),
        ];
    }
}
