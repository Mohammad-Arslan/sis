<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankPaymentValidation extends FormRequest
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
        //\Log::info('request from validation');
        //\Log::info($this->request->all());

        return [
            'invoice_code' => 'required',
            'payment_status' => 'required',
            'email' => 'required',
            'password' => 'required',
            'amount_received' => 'required_if:payment_status,PAID|numeric',
            'payment_received_date' => 'required_if:payment_status,PAID|date_format:d-m-Y',
        ];
    }
}
