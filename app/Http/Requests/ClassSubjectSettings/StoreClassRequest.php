<?php

namespace App\Http\Requests\ClassSubjectSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
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
            'class_name' => 'required|string|max:255|unique:com_classes,class_name',
            'abbreviation' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'attendance_type_id' => 'nullable|exists:attendance_types,id',
            'sort' => 'nullable|integer|min:0|unique:com_classes,sort',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'class_name.required' => 'Class name is required.',
            'class_name.unique' => 'This class name already exists.',
            'class_name.max' => 'Class name must not exceed 255 characters.',
            'abbreviation.max' => 'Abbreviation must not exceed 50 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'attendance_type_id.exists' => 'The selected attendance type is invalid.',
            'sort.integer' => 'Sort number must be an integer.',
            'sort.min' => 'Sort number must be at least 0.',
            'sort.unique' => 'This sort number already exists.',
        ];
    }
}
