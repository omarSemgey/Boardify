<?php

namespace App\Domains\Boards\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $board = $this->route('board');
        return $this->user()->can('update', $board);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'name' => 'sometimes|bail|string|min:1|max:255',
            'description' => 'sometimes|bail|nullable|string|max:500',
            'visibility' => 'sometimes|:in:public,semi-public,private',
        ];
    }
}
