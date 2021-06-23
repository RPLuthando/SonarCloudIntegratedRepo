<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginErrorRequest as LoginErrorRequest;
use App\Mail\RapidAssuranceMail;
use App\User;
use Exception;
use Mail;

class LoginController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    //login email only via otp page 
    public function loginUser(LoginErrorRequest $request)
    {
        
        try {
            $opt_email = $request->email;
            $user = User::where('email', $opt_email)->first();
            //Karl and Jacques x2

            if($opt_email == 'jacques+1@rapidassurance.ai' || 'jacques@rapidassurance.ai' || 'karl@rapidassurance.ai') {
                $otp = '24680';
                if ($user) {
                    $user->otp_verification = $otp;
                    if (!empty($user->otp_verification)) {
                        $user->save(); 
                    } else {
                        $user->update();
                    }
                    return view('auth.passwords.enter_login_otp')->with('email', $user->email)->with('otp', $otp);
                } else {
                    return redirect()->to('/login')->with('error_message', config('messages.msgs.wrong_user'));
                }
            } else {
                $otp = rand(111111, 999999);
                if ($user) {
                    $user->otp_verification = $otp;
                    if (!empty($user->otp_verification)) {
                        $user->save();
                    } else {
                        $user->update();
                    }
                    Mail::to($opt_email, ['user' => $user])->send(new RapidAssuranceMail($user));
                    return view('auth.passwords.enter_login_otp')->with('email', $user->email)->with('otp', $otp);
                } else {
                    return redirect()->to('/login')->with('error_message', config('messages.msgs.wrong_user'));
                }
            }
            
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

    }

}
