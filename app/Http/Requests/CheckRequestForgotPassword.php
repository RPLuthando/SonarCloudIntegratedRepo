<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckRequestForgotPassword extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
            'email'=>'required|email|exists:users',
            
        ];
    }
    public function messages(){
        return [
            'email.required' => 'Please Enter Email First!',
            'email.exists'=>'Email is not registered with us!'
        ];
    }
}
