<?php

namespace App\Http\Requests\TaxSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxTypeRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:tax_types,name',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tax type name is required.',
            'name.unique' => 'This tax type name already exists.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }
}
