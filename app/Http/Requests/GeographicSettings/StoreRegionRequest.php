<?php

namespace App\Http\Requests\GeographicSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegionRequest extends FormRequest
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
            'region_name' => 'required|string|max:255|unique:regions,region_name',
            'abbreviation' => 'required|string|max:10',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'region_name.required' => 'Region name is required.',
            'region_name.unique' => 'This region name already exists.',
            'abbreviation.required' => 'Abbreviation is required.',
        ];
    }
}
