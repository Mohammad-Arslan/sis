<?php

namespace App\Http\Requests\GeographicSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Implement proper authorization policy
        // For now, allow if user is authenticated
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $countryId = $this->route('country')->id;
        
        return [
            'country_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'country_name')->ignore($countryId)
            ],
            'abbreviation' => 'required|string|max:10',
            'country_code' => 'required|string|max:10',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'country_name.required' => 'Country name is required.',
            'country_name.unique' => 'This country name already exists.',
            'abbreviation.required' => 'Abbreviation is required.',
            'country_code.required' => 'Country code is required.',
        ];
    }
}

