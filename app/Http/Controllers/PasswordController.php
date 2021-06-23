<?php

namespace App\Http\Controllers;
use App\User;
use Mail;
use Auth;
use Exception;
use Redirect;
use Illuminate\Http\Request;
use App\Mail\RapidAssuranceMail as RapidAssuranceMail;
use App\Http\Requests\CheckRequestForgotPassword as CheckRequestForgotPassword;
use App\Http\Requests\EnterVerifiedOtp as EnterVerifiedOtp;
class PasswordController extends Controller
{
    
    // otp confirmation page
    public function confirmPasscode(Request $request){       
        try{
            $user = User::where('email',$request->otpemail)->first();
            if((int)($request->otp) == $user->otp_verification){
                return response(['success'=>true,'user'=>$user,'message'=>config('messages.msgs.success')]); 
            }else{ 
                return response(['success'=>false,'message'=>config('messages.msgs.otp_failed')]);
            }
        }
        catch(Exception $ex){
            $this->commonLog($ex);
            return redirect()->to('/login')->with('error_message',config('messages.msgs.went_wrong'));
        }
    }
    /// redirect to dashboard after otp confirmation page
    public function loginUserViaEmail($otpemail){
        $user = User::where('email',$otpemail)->first();        
        if($user){
            Auth::login($user);
            return redirect('/');
        }else{
            return redirect()->to('/login')->with('error_message',config('messages.msgs.wrong_user'));
        }
    }
    
}
