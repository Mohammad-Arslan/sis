<?php

namespace App\Http\Requests\GeographicSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
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
            'city_name' => 'required|string|max:255|unique:cities,city_name',
            'abbreviation' => 'required|string|max:10',
            'state_id' => 'required|exists:states,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'city_name.required' => 'City name is required.',
            'city_name.unique' => 'This city name already exists.',
            'abbreviation.required' => 'Abbreviation is required.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'Selected state does not exist.',
        ];
    }
}
