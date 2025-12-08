<?php

namespace App\Http\Requests\TaxSettings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxRequest extends FormRequest
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
            'tax_type_id' => 'required|exists:tax_types,id',
            'state_id' => 'required|exists:states,id',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'active_from' => 'required|date',
            'active_till' => 'required|date|after:active_from',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tax_type_id.required' => 'Tax type is required.',
            'tax_type_id.exists' => 'Selected tax type does not exist.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'Selected state does not exist.',
            'tax_percentage.required' => 'Tax percentage is required.',
            'tax_percentage.numeric' => 'Tax percentage must be a number.',
            'tax_percentage.min' => 'Tax percentage cannot be negative.',
            'tax_percentage.max' => 'Tax percentage cannot exceed 100.',
            'active_from.required' => 'Active from date is required.',
            'active_from.date' => 'Active from must be a valid date.',
            'active_till.required' => 'Active till date is required.',
            'active_till.date' => 'Active till must be a valid date.',
            'active_till.after' => 'Active till date must be after active from date.',
        ];
    }
}
