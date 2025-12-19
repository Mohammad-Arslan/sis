<?php

namespace App\Http\Requests\AcademicSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentPreviousSchoolRequest extends FormRequest
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
            'school_name' => 'required|string|max:255|unique:student_previous_schools,school_name',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'school_name.required' => 'School name is required.',
            'school_name.unique' => 'This school name already exists.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }
}
