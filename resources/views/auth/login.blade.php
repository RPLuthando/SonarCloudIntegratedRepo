@extends('layouts.login_backend')

@section('content')
    <div class="login-title">
            <h3>{{ __('Login') }}</h3>
    </div>
    <div class="login-form">
        <form method="POST" action="{{url('/login-site')}}">
            @csrf
            <div class="row">
                <div class="form-group col-md-6 offset-col-3 m-auto">
                    <div class="logininput">
                        <label class="mb-3">Please enter your email address to get started</label>
                        <input  id="email" type="email" name="email" class="login-input{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="E-Mail address" required autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        <button type="submit" class="btn login-btn mt-3">Next</button>
                    </div>
                </div> 
            </div>
        </form>
    </div>
@endsection                 