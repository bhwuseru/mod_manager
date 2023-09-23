<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModListCsvStoreRequest extends FormRequest
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
            'csv_file' => 'required|file|mimes:csv|max:2048', // 最大2MBのCSVファイルを許可
        ];
    }
}
