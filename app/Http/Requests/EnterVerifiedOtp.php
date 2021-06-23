<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterVerifiedOtp extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [ 
            'otp'=>'required|numeric|max:6',   
        ];
    }
    public function messages(){
        return [
            'otp.required' => 'Please Enter Valid Otp',
            'otp.numeric'=>'Otp is 6 digit number not alpha numeric',
            'otp.max'=>'Maximum value allowed is 6 digits'       
        ];
    }
}
