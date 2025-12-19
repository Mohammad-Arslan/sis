<?php

namespace App\Http\Requests\AcademicSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
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
        $language = $this->route('language');
        $languageId = $language instanceof \App\Models\Language ? $language->id : $language;

        return [
            'language_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('languages', 'language_name')->ignore($languageId),
            ],
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
