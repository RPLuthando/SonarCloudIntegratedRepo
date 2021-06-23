@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row">
        <form action="{{url('/update-review-list')}}" method="post">
            @csrf
            <input name="response_id" type="hidden" value="{{$surveyOptions->id}}">
                <div class="col-md-12">
                    <div>
                        <h2>
                            {{ __('lang.edit_this_question')}}
                        </h2>
                        <div class="hidden">
                            <h5>
                                {{ __('lang.question')}} : {{$surveyOptions->questionsView->question_name}}
                            </h5>
                            <ul class="review-list list-unstyled">
                                
                                    
                                    
                                        <li> > 
                                            @if( $surveyOptions->other )
                                               Other -  {{$surveyOptions->other}} 
                                            @else
                                                {{$surveyOptions->optionDetails->question_options}}
                                            @endif
                                            @if( $surveyOptions->optionDetails->ideal_standard == 'Yes' )
                                                <i aria-hidden="true" class="fa fa-star">
                                                </i>
                                            @elseif( $surveyOptions->optionDetails->acceptable_standard == 'Yes')
                                                <i aria-hidden="true" class="fa fa-star-half-o">
                                                </i>
                                            @else
                                                <i aria-hidden="true" class="fa fa-star-o">
                                                </i>
                                            @endif
                                        </li>
                               
                                    
                                </li>
                                <span>
                                    (Response submitted on {{ \Carbon\Carbon::parse($surveyOptions->updated_at)->format('d/m/Y H:s')}})
                                </span>
                            </ul>
                            <h5>
                                {{ __('lang.has_this_change')}}
                            </h5>
                            <div class="mb-4">
                                <input class="review_yes btn btn-primary" idcorrect="{{$question_id}}" type="button" value="Yes"/>
                                <a href="{{url('/review-check/'.$survey_id)}}">  
                                    <input class="review_no btn btn-primary"  idsurvey="{{$survey_id}}" idwrong="{{$question_id}}" type="button" value="No"/>
                                </a>
                            </div>
                            
                        </div>
                    </div>

                    <div id="wholeBlock" style="display:none;">

                        <h5 class="hidden" id="textreview{{$question_id}}">
                            {{ __('lang.whats_in_place')}}
                        </h5>
                        <ul class="ml-0 pl-3">
                            @foreach ($savedValues as $options)
                               <li class="list-unstyled">
                                    <label>
                                        <input type="{{$options->option_type}}"  name="option_val" class="questionOption" question_texts="{{$surveyOptions->questionsView->question_name}}" ques_id="{{$options->question_id}}"  @if($surveyOptions->option_id == $options->id) checked @endif value="{{$options->id}}" id="question{{$options->question_id}}{{$loop->iteration}}"> 
                                        {{$options->question_options}}
                                    </label>
                                </li> 
                            @endforeach
                        </ul>
                        @if( $surveyOptions->other !== null )
                        <div class="col-md-12" id="optionActive" style="display:none;">
                            <input class="form-control mb-4 col-4" id="otherFields{{$question_id}}" name="other" placeholder="Enter Other Field" ques_id="{{$question_id}}" type="text" value="{{$surveyOptions->other}}">
                           
                        </div>
                        @else
                        <div class="col-md-12" id="optionActive" style="display:none;">
                            <input class="form-control mb-4 col-4" id="otherFields{{$question_id}}" name="other" placeholder="Enter Other Field" ques_id="{{$question_id}}" type="text" value="{{$surveyOptions->other}}">
                           
                        </div>
                        @endif
                   
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-info edit_update" id="{{$question_id}}"  value="Update"/>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
</script>
{!!Html::script('assets/backend/js/edit_review_form.js')!!}
