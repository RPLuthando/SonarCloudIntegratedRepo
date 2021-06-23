@extends('layouts.backend')
{!!Html::style('assets/backend/css/custom-file.css')!!}
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('OTP verification') }}</div> 
                    
                    <div class="card-body">
                        <form method="POST" id="sendOtpForm">
                            @csrf
                            <input type="hidden"  name="otp_email" id="otp_email" value="{{$email}}">
                            <div class="form-group row">
                                <label for="otp" class="col-md-9 col-form-label text-md-right">{{ __('Enter 6 digit OTP sent at '.$email.'') }}</label>
    
                                <div class="col-md-3">
                                    <input id="otp_val" type="number" class="form-control{{ $errors->has('otp') ? ' is-invalid' : '' }}" name="otp" pattern="^\d{6}$" value="" required autofocus>
    
                                    {{-- @if ($errors->has('otp')) --}}
                                        <span id="show_alert"  class="invalid-feedback d-none" role="alert">
                                            <strong>{{ $errors->first('otp') }}</strong>
                                        </span>
                                    {{-- @endif --}}
                                </div>
                            </div> 
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" id="submit_otp" class="btn btn-primary">
                                        {{ __('VERIFY') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection