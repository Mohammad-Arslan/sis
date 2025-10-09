<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:32',
            'description' => 'nullable|min:2|max:128',
            'display_name' => 'nullable|min:2|max:32',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Role name required.',
            'name.min' => 'Role name needs to min. 2',
            'name.max' => 'Role name can max be. 32',
            'description.min' => 'Description needs to min. 2',
            'description.max' => 'Description name can max be. 128',
            'display_name.min' => 'Display name needs to min. 2',
            'display_name.max' => 'Display name can max be. 32',
        ];
    }
}
