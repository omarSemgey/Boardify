<?php

namespace App\Domains\Roles\Requests;

use App\Domains\Roles\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('global-permission', [Role::class, 'role', 'create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'bail|required|string|min:1|max:250',
            'description' => 'bail|nullable|string|max:500',
            'type_id' => 'bail|required|integer|exists:types,id',
            'board_id' => 'bail|required|integer|exists:boards,id',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required:integer|exists:permissions,id',
        ];
    }
}