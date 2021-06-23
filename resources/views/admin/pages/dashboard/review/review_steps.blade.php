@extends('layouts.backend')

@section('content')
{!!Html::style('assets/backend/css/multistep_review.css')!!}
<div class="container">
    <div class="col">
        <div>
            <h2>
                {{ __('lang.review')}}: {{ __('lang.title-review')}}
            </h2>
            <form action="{{url('update-review-results')}}" id="reviewForm" method="post">
                @csrf
            		@php
            			$counts = 1;
            		@endphp
               
                <input id="total_questions" name="total_questions" type="hidden" value="{{count($questionOptions)}}">
                    <input id="survey_id" name="survey_id" type="hidden" value="{{$id}}">
                    @foreach($questionOptions as $optionsView)
                        <fieldset data-id="{{$loop->iteration}}" id="multi{{$loop->iteration}}">
                            <h5>
                                {{$optionsView['questions_get']['question_name']}}
                            </h5>
                            <ul class="review-list list-unstyled">
                                
                                     @if( $optionsView['question_options'] === 'Other' )
                                        <li>  >  Other - {{ $optionsView['response_date']['other'] }} 
                                            @if( $optionsView['ideal_standard'] == 'Yes' )
                                                <i aria-hidden="true" class="fa fa-star"></i>
                                            @elseif( $optionsView['acceptable_standard'] == 'Yes')
                                                <i aria-hidden="true" class="fa fa-star-half-o"></i>
                                            @else
                                                <i aria-hidden="true" class="fa fa-star-o"></i>
                                            @endif
                                        </li>
                                    @else
                                        <li> > {{ $optionsView['question_options'] }} 
                                            @if( $optionsView['ideal_standard'] == 'Yes' )
                                                <i aria-hidden="true" class="fa fa-star"></i>
                                            @elseif( $optionsView['acceptable_standard'] == 'Yes')
                                                <i aria-hidden="true" class="fa fa-star-half-o"></i>
                                            @else
                                                <i aria-hidden="true" class="fa fa-star-o"></i>
                                            @endif
                                        </li>
                                    @endif
                                    

                                <span>
                                    (Response submitted on {{ \Carbon\Carbon::parse($optionsView['response_date']['updated_at'])->format('d/m/Y H:s')}})
                                </span>
                            </ul>
                            <h5>
                               {{ __('lang.has_this_change')}}
                            </h5>
                            <div class="mb-4">
                                <input class="review_yes btn btn-primary" idcorrect="{{$loop->iteration}}" type="button" value="Yes"/>
                                <input class="review_no btn btn-primary" idsurvey="{{$id}}" idwrong="{{$loop->iteration}}" type="button" value="No"/>
                            </div>
                            <h5 class="hidden" id="textreview{{$loop->iteration}}">
                                {{ __('lang.whats_in_place')}}
                            </h5>
                            @php
	        						$optionsGetViaId = getOptionsList($optionsView['questions_get']['id']);
							@endphp
                            <ul class="list-unstyled hidden" id="optionsreview{{$loop->iteration}}">
                                @foreach($optionsGetViaId as $optionsName)
                                <li>
                                    <label>
                                        <input class="reviewOption option_{{$counts}}" id="reviewOption{{$counts}}{{$loop->iteration}}" name="optionnames[{{$optionsView['questions_get']['id']}}]" question_text="{{$optionsName['question_options']}}" rev_id="{{$counts}}" type="{{$optionsName['option_type']}}" value="{{$optionsName['id'] ?: null }}">
                                            {{$optionsName['question_options']}}
                                        </input>
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                            <div>
                                <input class="hidden form-control mb-4 col-4" id="otherField{{$loop->iteration}}" name="other[{{$optionsView['questions_get']['id']}}]" placeholder="Enter Other Field" style="display: none" type="text">
                                </input>
                            </div>
                            <button class="hidden review_next btn btn-primary" id="nextreview{{$loop->iteration}}" idget="{{$loop->iteration}}" options_id="option_{{$counts}}" type="button">
                                {{ __('lang.next_question')}}
                            </button>
                        </fieldset>
                        @php
		        			$counts++;
		        		@endphp
	            	@endforeach
                        <div class="row cls" style="display: none;">
                            <div class="col-md-12">
                                <button class="btn btn-danger" id="review_submit" survey_id="{{$id}}" rev_submit_id="{{count($questionOptions)}}" type="submit">
                                   {{ __('lang.review_entries')}}
                                </button>
                            </div>
                        </div>
                    </input>
                </input>
            </form>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{!!Html::script('assets/backend/js/multistep_review.js')!!}
