<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginErrorRequest extends FormRequest
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
 
    public function rules()
    {
        return [ 
            'email'=>'required|email|max:255',
            // 'password'=>'required'
        ];
    }
    public function messages(){
        return [
            'email.required'=>'Email field is required! Please Fill it!',
            'email.email'=>'Make Sure the Format is Correct.',
            'email.max'=>'Please Make sure your email id has 255 maximum characters.',
            // 'password.required'=>'Please Enter the Password too.'
        ];
    }
}
