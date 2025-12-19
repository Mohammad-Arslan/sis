<?php

namespace App\Http\Requests\GeographicSettings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuildingTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Implement proper authorization policy
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type_name' => 'required|string|max:255',
            'type_description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type_name.required' => 'Type name is required.',
        ];
    }
}
