@extends('layouts.backend')

@section('content')
{!!Html::style('assets/backend/css/survey_start.css')!!}
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
         @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
        <form method="post" action="{{ url('/show-response') }}" id="surveyForm">
            @csrf
           {{--  <input type="hidden" name="survey_id" value="{{$qus->category->id}}"> --}}
                       
            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
            @foreach($categoryname as $category)
            <input type="hidden" name="owner_id" value="{{$category->survey_owner_id}}">
            @endforeach
            <input type="hidden" id="total_questions" value="{{$question->count()}}" name="total_questions"> 
           @php
            $i=0;
           @endphp
            @foreach ($question as $qus)
             
            <input type="hidden" name="survey_id" value="{{$qus->question_cat_id}}"> 
            <input type="hidden" value="{{$qus->id}}" name="question_id[]">
            <div id="fieldsets" >
              <fieldset class="next decorate current_question{{$loop->iteration}}" id="fieldlist{{$loop->iteration}}"  style="display:none;">
                <h5 for="title">{{$qus->question_name}}</h5>
                    <ul class="list-unstyled">
                      @foreach ($qus->questionoption as $opt)
                     
                      <li>   
                           <label> <input 
                            type="{{$opt->option_type}}" 
                            class="questionOption option_{{$i}}" 
                            id="optionid{{$opt->question_id}}{{$loop->iteration}}" 
                            ques_id="{{$opt->question_id}}"  
                            name="option_id[{{$i}}]" 
                            value="{{ $opt->id }}" question_text="{{$opt->question_options}}"> {{$opt->question_options}}</label>
                        </li>   
                       
                        @endforeach
                    </ul>
                      <input type="text" style="display: none"  id="otherField{{$qus->id}}" ques_id="{{$qus->id}}" class=" form-control mb-4 col-4" placeholder="Enter Other Field" name="other{{$qus->id}}">
                     <button type="button" class="btn btn-info nextBtn"  options_id=option_{{$i}} question_id="{{$loop->iteration}}"> Next Question</button>
                     

                </fieldset>
            </div>
            @php
              $i++;
            @endphp
            @endforeach
            <div class="row cls" style="display: none;">
              <div class="col-md-12" >
                 <button type="submit" style="display: none;" id="reviewResponseBtn" class="btn btn-danger" >REVIEW YOUR ENTRIES</button>
              </div>
            </div>
        </form>
      </div>
    </div>
</div>
@endsection
@section('scripts')
{!!Html::script('assets/backend/js/survey_start.js')!!}
@endsection
