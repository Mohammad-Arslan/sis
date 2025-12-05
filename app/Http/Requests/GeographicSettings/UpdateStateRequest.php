<?php

namespace App\Http\Requests\GeographicSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStateRequest extends FormRequest
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
        $stateId = $this->route('state')->id;
        
        return [
            'state_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('states', 'state_name')->ignore($stateId)
            ],
            'country_id' => 'required|exists:countries,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'state_name.required' => 'State name is required.',
            'state_name.unique' => 'This state name already exists.',
            'country_id.required' => 'Country is required.',
            'country_id.exists' => 'Selected country does not exist.',
        ];
    }
}

