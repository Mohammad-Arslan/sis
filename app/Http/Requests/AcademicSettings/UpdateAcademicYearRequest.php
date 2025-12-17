<?php

namespace App\Http\Requests\AcademicSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicYearRequest extends FormRequest
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
        $academicYear = $this->route('academicYear');
        $academicYearId = $academicYear instanceof \App\Models\AcademicYear ? $academicYear->id : $academicYear;
        
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_years', 'title')->ignore($academicYearId),
            ],
            'active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Academic year title is required.',
            'title.unique' => 'This academic year title already exists.',
        ];
    }
}

