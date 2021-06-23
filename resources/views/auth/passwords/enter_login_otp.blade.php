@extends('layouts.login_backend')

@section('content') 
                  
    <div class="login-title">
        <h3>{{ __('OTP verification') }}</h3>
                       <!-- <h3>OTP verification</h3> -->
    </div>
    <div class="login-form">
        <form method="POST" id="sendOtpForm">
            @csrf
            <input type="hidden"  name="otp_email" id="otp_email" value="{{$email}}">
            <div class="row">
                <div class="form-group col-md-6 offset-col-3 m-auto">
                    <div class="logininput">
                    <!-- <label class="mb-3">Enter 6 digit OTP sent at responsibleuser@yopmail.com</label> -->
                        <label class="mb-3">{{ __('Enter 6 digit OTP sent at '.$email.'') }}</label>
                        <input id="otp_val" type="text" class="login-input{{ $errors->has('otp') ? ' is-invalid' : '' }}" name="otp" pattern="^\d{6}$" value="" required autofocus>
                        <button type="submit" id="submit_otp" class="btn login-btn mt-3">{{ __('VERIFY') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection                 
                   