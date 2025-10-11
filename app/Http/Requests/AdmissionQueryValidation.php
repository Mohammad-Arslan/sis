<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionQueryValidation extends FormRequest
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
            'student_name' => 'required',
            'student_age' => 'required',
            'parent_name' => 'required',
            'parent_email' => 'required',
            'parent_contact' => 'required',
            'city_id' => 'required',
            'town_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'source_id' => 'required',
        ];
    }
}
