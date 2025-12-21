<?php

namespace App\Http\Requests\SubjectSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject_name' => ['required', 'string', 'max:255'],
            'language_id' => ['required', 'integer', 'exists:languages,id'],
            'is_academic' => ['required', 'boolean'],
            'subject_type' => ['required', 'string', 'max:255'],
            'subject_group_id' => ['nullable', 'integer', 'exists:subject_groups,id'],
            'abbreviation' => ['nullable', 'string', 'max:50'],
            'sort_no' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'subject_name.required' => 'Subject name is required.',
            'subject_name.max' => 'Subject name must not exceed 255 characters.',
            'language_id.required' => 'Language is required.',
            'language_id.exists' => 'The selected language is invalid.',
            'is_academic.required' => 'Academic status is required.',
            'is_academic.boolean' => 'Academic status must be true or false.',
            'subject_type.required' => 'Subject type is required.',
            'subject_group_id.exists' => 'The selected subject group is invalid.',
            'abbreviation.max' => 'Abbreviation must not exceed 50 characters.',
            'sort_no.integer' => 'Sort number must be an integer.',
            'sort_no.min' => 'Sort number must be at least 0.',
        ];
    }
}
