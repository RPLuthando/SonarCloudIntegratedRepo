@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            
        <form method="post" id="msform">
                @csrf
                
                @foreach ($categoryname as $catname )
                    <h3>{{$catname->question_cat_name}}</h3>
                   
                    <hr>
                    @php 
                    $user = auth()->user(); 
                    @endphp
                    <input type="hidden" name="question_cat_id" value="{{$catname->id}}">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <input type="hidden" name="owner_id" value="{{$catname->survey_owner_id}}">
                @endforeach

                <div id="fieldsets" >
                      <?php $iconter = 1;?>
                      @foreach ($question as $qus)

                        <fieldset class="next current_question{{$loop->iteration}}" id="fieldlist{{$loop->iteration}}"  style="border:1px solid grey;padding:13px 13px;margin:12px 12px;display: none">
                        {{-- <fieldset class="next current_question{{$loop->iteration}}" id="fieldlist{{$loop->iteration}}" @if(isset($question_id)) @if($question_id==$loop->iteration) style="display: block!important;border:1px solid grey;padding:13px 13px;margin:12px 12px;" @endif  style="border:1px solid grey;padding:13px 13px;margin:12px 12px;display: none" @endif> --}}
                                <h5 for="title">{{$qus->question_name}}</h5>
                                <ul class="list-unstyled"> 
                                    @foreach ($qus->questionoption as $opt)
                                   
                                    <li> <?php $quesOptArr = json_encode( [ $opt->question_id => $opt->id ] ); ?>
                                        {{$quesOptArr}}
                                        {{-- <input type="{{$opt->option_type}}" class="questionOption" id="optionid{{$opt->question_id}}{{$loop->iteration}}" ques_id="{{$opt->question_id}}"  name="question_id{{$opt->question_id}}" value="{{$opt->id}}"> {{$opt->question_options}} --}}
                                        <input 
                                        type="{{$opt->option_type}}" 
                                        class="questionOption" 
                                        id="optionid{{$opt->question_id}}{{$loop->iteration}}" 
                                        ques_id="{{$opt->question_id}}"  
                                        name="question_id[{{ $iconter }}]" 
                                        value="{{ $quesOptArr }}"> {{$opt->question_options}}
                                    </li>

                                    @endforeach
                                  <?php  $iconter++; ?>
                                </ul>
                            <button type="button" class="btn btn-info nextBtn" question_id="{{$loop->iteration}}"> Next Question</button>
                            <button type="button" class="surveyresponse btn btn-danger action-button" >Submit Response</button>
                            </fieldset> 
                     @endforeach
                    
                   
                            
                <input type="hidden" id="total_questions" name="total_questions" value="{{$question->count()}}">
                </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('/assets/backend/js/surveyprocess.js')}}"></script> 
@stop
