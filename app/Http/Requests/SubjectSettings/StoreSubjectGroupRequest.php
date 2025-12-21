<?php

namespace App\Http\Requests\SubjectSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectGroupRequest extends FormRequest
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
            'subject_group_name' => ['required', 'string', 'max:255', 'unique:subject_groups,subject_group_name'],
            'description' => ['nullable', 'string', 'max:1000'],
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
            'subject_group_name.required' => 'Subject group name is required.',
            'subject_group_name.unique' => 'This subject group name already exists.',
            'subject_group_name.max' => 'Subject group name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
        ];
    }
}
