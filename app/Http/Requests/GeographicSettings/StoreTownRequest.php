<?php

namespace App\Http\Requests\GeographicSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreTownRequest extends FormRequest
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
            'town_name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'town_name.required' => 'Town name is required.',
            'city_id.required' => 'City is required.',
            'city_id.exists' => 'Selected city does not exist.',
        ];
    }
}
