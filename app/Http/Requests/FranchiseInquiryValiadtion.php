<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FranchiseInquiryValiadtion extends FormRequest
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
            'full_name' => 'required',
            'CNIC' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city_id' => 'required|exists:sources,id',
            'contact_no_1' => 'required',
            'contact_no_2' => 'required',
            'current_occupation' => 'required',
            'led_franchise' => 'required|in:yes,no',
            'franchise_name' => 'required_if:led_franchise,yes',
            'franchise_interest' => 'required|in:new_franchise,convert_existing',
            'area_location' => 'required',
            'source_id' => 'required|exists:sources,id',
        ];
    }
}
