<?php

namespace App\Http\Requests\TaxSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaxTypeRequest extends FormRequest
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
        $taxType = $this->route('taxType');
        $taxTypeId = $taxType instanceof \App\Models\TaxType ? $taxType->id : $taxType;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tax_types', 'name')->ignore($taxTypeId),
            ],
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
