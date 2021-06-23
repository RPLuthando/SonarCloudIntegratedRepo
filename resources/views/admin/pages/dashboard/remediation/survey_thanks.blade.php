@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        
    <div class="col-md-12">
        <div>
            <h2>Thanks for taking part</h2>
        </div>
        @php
            $userInfo = fetch_user_info($ownerValue);
        @endphp
        <div>

            <div class="mt-4">
                <p>If there are any further actions from the results of this survey, we will be in touch.</p>
                   <p><b>Questions?</b><br/>
                   Contact the survey owner:<br/>
                     {{$userInfo->name}}<br/>
                     <a href="#">{{$userInfo->email}}</a></p>
            </div>
        
        </div>
       
    <br>
    <h5> If You have other surveys to complete: </h5>
    <a class="btn btn-primary" href="{{url('/')}}" role="button">My Dashboard</a> 
   
</div>
</div>
</div>
@endsection

