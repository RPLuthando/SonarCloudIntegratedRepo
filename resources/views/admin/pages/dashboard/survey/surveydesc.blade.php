@extends('layouts.responsible_backend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if($ques_cat != null)
        <div class="col-md-9"> 
                
                <h3>{{$ques_cat->name}}</h3>
                <hr>
                <p><span style="font-family:'OpenSans-Regular', 'Open Sans', sans-serif;font-weight:400;">Type: <b></b><span></p>
                    @php 
                        $startdate = date("m/d/Y", strtotime($ques_cat->created_at));
                        $enddate = date("m/d/Y", strtotime($ques_cat->deadline));
                    @endphp
                <p><span style="font-family:'OpenSans-Regular', 'Open Sans', sans-serif;font-weight:400;">Issued: {{$startdate}}<span></p>
                    
                <p><span style="font-family:'OpenSans-Regular', 'Open Sans', sans-serif;font-weight:400;">Deadline: {{$enddate}}<span></p>
                <p><span style="font-family:'OpenSans-Regular', 'Open Sans', sans-serif;font-weight:400;">Entity: {{$entityDetails->name}}<span></p>
                <p><span style="font-family:'OpenSans-Regular', 'Open Sans', sans-serif;font-weight:400;">Survey owner: {{$ques_cat->username}}<span></p>
            <hr>
        <div>
               {{-- <b> Summary:</b>
               <p>Survey Summery</p> --}}
        </div>
        

        <!-- <a class="sv_button_link btn btn-primary" data-bind="attr: { href: '/surveyrun?id={{$ques_cat->id}}&&en={{$entityDetails->id}}' }">Start Survey</a> -->
     <a class="sv_button_link btn btn-primary" href="{{ url('surveyrun?id='.$ques_cat->id.'&&en='.$entityDetails->id) }}">Start Survey</a>
      
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error) 
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        </div>
        @else
        <div class="alert alert-danger">
            <ul>
               
                <li>No Data Found!</li>

            </ul>
        </div>
        @endif
    </div>
</div>
<!-- <script src="{{ asset('assets/backend/js/surveyjs/index_survey.js') }}"></script>    -->
@endsection
