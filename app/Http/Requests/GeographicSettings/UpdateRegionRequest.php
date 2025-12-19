<?php

namespace App\Http\Requests\GeographicSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegionRequest extends FormRequest
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
        $regionId = $this->route('region')->id;

        return [
            'region_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('regions', 'region_name')->ignore($regionId)
            ],
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
