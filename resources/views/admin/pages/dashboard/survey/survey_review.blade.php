@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        @php
        $fetch = fetch_questions_via_survey_id($survey_id);
        // dd($fetch);
        @endphp
        <div class="col-md-12">         
            <div>
                <h2>Review your responses:</h2>
            </div>
            <div class="row mt-5">
                @foreach($fetch as $ques)
                <div class="col-md-12 question">
                     <h5>{{$ques->question_name}}</h5>
                </div>
                @php 
                $response = fetch_response_via_question_id($ques->id);
                // dd($response->other);
                @endphp
                <div class="col-md-12 response mb-3">
                     @if(fetch_option_detail($response->option_id)->question_options == 'Other' )
                      Other - {{$response->other}}
                     @else
                        {{fetch_option_detail($response->option_id)->question_options}} 
                    @endif 
                        <a href="{{url('/edit-response/'.$ques->id.'/'.$survey_id.'')}}">Change</a>
                     
                </div>
                @endforeach
            </div>

            <br>
    <p> If you are satisfied with your answers, then you can submit your responses by clicking the button below:</p>
            <div class="row">
                <div class="col-md-12" id="clearDiv">

                    <a c_id="{{$response->survey_id}}" href="{{url('/final-submit/'.$response->survey_id.'')}}" id="submit_initial"  class="btn btn-danger submit_initial">SUBMIT YOUR RESPONSE</a>
                </div>
            </div>  

        </div>
         

    </div>
</div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js">
</script>
{!!Html::script('assets/backend/js/survey_review.js')!!}