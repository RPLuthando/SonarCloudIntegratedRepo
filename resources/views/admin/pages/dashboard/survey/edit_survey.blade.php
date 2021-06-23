@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row justify-content-center">    
    <form method="post" action="{{url('/update-response')}}">    
        @csrf
        <input type="hidden" name="response_id" value="{{$response->id}}">
        <div class="col-md-12">
            <div>
                <h2>Edit this question:</h2>
            </div> 
            @php
            $question = fetch_question_detail($response->question_id);
            @endphp
            <div> 
            <h5>{{ __('lang.question')}} : {{$question->question_name}}</h5> 
          
            <ul class="ml-0 pl-3">
                
            @foreach ($options as $opt)
                <li class="list-unstyled">
                    <label><input type="{{$opt->option_type}}"   name="option_val" question_texts="{{$opt->question_name}}" class="questionOption" ques_id="{{$opt->question_id}}"  @if($response->option_id== $opt->id) checked @endif value="{{$opt->id}}"> 
                    {{$opt->question_options}}</label>
                </li>
            @endforeach
            </ul> 
           
            </div>

            @if($response->other!==null)
            <div class="col-md-12" id="optionActive" style="display:none;">
                <input type="text"  id="otherFields{{$question->id}}" ques_id="{{$question->id}}" class="form-control  mb-4 col-4" value="{{$response->other}}" placeholder="Enter Other Field" name="other">
            </div>
            @else
                <div class="col-md-12" id="optionActive" style="display:none;">
                    <input type="text"  id="otherFields{{$question->id}}" ques_id="{{$question->id}}" class="form-control  mb-4 col-4" value="{{$response->other}}" placeholder="Enter Other Field" name="other">
                </div>
            @endif
        </div>
        <div class="col-md-12">
             <input type="submit" class="btn btn-info" value="Update" id="{{$question->id}}"  />  
        </div>
    </form>
    </div>
</div>
@endsection
@section('scripts')

{!!Html::script('assets/backend/js/edit_survey.js')!!}
@endsection
