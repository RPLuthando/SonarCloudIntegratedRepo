@extends('layouts.backend')
@section('content')

<div class="container">
   <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has($msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
            @endif
        @endforeach
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <h2>
                    {{ __('lang.review_responses')}}:
                </h2>
            </div>
            <div class="row mt-5">
                @foreach($surveyOptions as $options)
                <div class="col-md-12 question">
                    <h5>
                        {{$options->questionsView->question_name}}
                    </h5>
                </div>
                <div class="col-md-12 response mb-3">
                    @if($options->optionDetails->question_options == 'Other' )
	        		  {{ __('lang.other')}} - {{$options->other}}
	        		@else
	        			{{$options->optionDetails->question_options}} 
	        		@endif
	        		
	        		<a href="{{url('/edit-review-list/'.$options->question_id.'/'.$options->survey_id.'')}}">
                    
                        {{ __('lang.change')}}
                    </a>
                </div>
                @endforeach
            </div>
            <br>
                <p>
                    {{ __('lang.satisfied_text')}}
                </p>
                <div class="row">
                    <div class="col-md-12" id="clearDiv">
                        <a class="btn btn-danger" href="{{url('/thank-you/'.$ownerId.'/'.$survey_id.'')}}" >
                            {{ __('lang.submit_your_response')}}
                        </a>
                    </div>
                </div>

            </br>
        </div>
    </div>
</div>
@endsection
