@extends('layouts.backend')

@section('content')
<div class="container">
       
    <div class="row justify-content-center">
            
        <div class="col-md-9">
            <div class="">
                <div class="card-body pt-0">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   
    <h2>Add new {{fancy_the_role($user_type)}}</h2>
    <form method="POST" action="/addinfo">
        @csrf
        <input type="hidden" value="{{$user_type}}" name="user_type">
        {{-- <input type="hidden" id="new_user_id" name="new_user_id" value="{{$email}}"> --}}
        <div class="form-group">
            <label for="new_user_name">Name:</label>
            <input type="text" class="form-control" id="new_user_name" name="new_user_name" value="{{ old('new_user_name') }}">
        </div>
        <div class="form-group">
            <label for="new_user_email">Email:</label>
            <input type="text" class="form-control" id="new_user_email" name="new_user_email" value="{{ old('new_user_email') }}">
        </div>
        {{-- <div class="form-group">
            <label for="new_user_password">Password:</label>
            <input type="password" class="form-control" id="new_user_password" name="new_user_password" value="{{ old('new_user_password') }}">
        </div>
        <div class="form-group">
            <label for="new_user_address">Address:</label>
            <input type="text" class="form-control" id="new_user_address" name="new_user_address" value="{{ old('new_user_address') }}">
        </div>
        <div class="form-group">
            <label for="new_user_phone">Contact information:</label>
            <input type="number" class="form-control" id="new_user_phone" name="new_user_phone" value="{{ old('new_user_phone') }}">
        </div> --}}
        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
