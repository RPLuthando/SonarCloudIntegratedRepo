<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNewUserlist extends FormRequest
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
            // 'new_user_name' => 'required|min:3|max:35|unique:users,name',
            'new_user_name' => 'required|min:3|max:35',
            'new_user_email'=>'required|email|unique:users,email',
           /*  'new_user_password'=>'required|min:3|max:20',
            'new_user_address' => 'required',
            'new_user_phone' => 'required|numeric' */
        ];
    }
    public function messages(){
        return [
            'new_user_name.required'=> 'The name field is required.',
            'new_user_name.min' => ' The name must be at least 3 characters.',
    		'new_user_name.max' => ' The name may not be greater than 35 characters.',
            'new_user_email.required'=>'Email field is required! Please Fill it!',
            'new_user_email.email'=>'Make Sure the Format of Email is Correct.',
            // 'new_user_password.required'=> 'Please fill the password field',
            // 'new_user_password.min'=>'Please Make sure your email id must be at least 5 characters.',
            // 'new_user_password.max'=>'Please Make sure your email id has 255 maximum characters.',
            // 'new_user_address.required'=> 'Please fill the address field',
            // 'new_user_phone.required'=> 'Phone number is required',
        ];
    }
}
