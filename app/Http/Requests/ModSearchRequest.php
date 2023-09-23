<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'nullable|integer|min:0',
            'directory_name' => 'nullable|string|min:1',
            'active' => 'nullable|boolean',
            'deactive' => 'nullable|boolean',
        ];
    }
}
