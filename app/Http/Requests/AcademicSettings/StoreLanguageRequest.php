<?php

namespace App\Http\Requests\AcademicSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'language_name' => 'required|string|max:255|unique:languages,language_name',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'language_name.required' => 'Language name is required.',
            'language_name.unique' => 'This language name already exists.',
        ];
    }
}
