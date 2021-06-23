@extends('layouts.survey_header')

@section('contents')
    {!!Html::style('assets/backend/css/dash_style.css')!!}
    <div class="survey-page-header col-md-10">
        <div class="sv_main survey-page-header-content">
{{--            <button onclick="window.location = '/'">&lt&nbspBack</button>--}}
            <a href="/" class="btn btn-primary start_btn1">Return to Dashboard</a>
        </div>
    </div>
    
        @csrf
        <div id="surveyElement" style="display:inline-block;width:100%;"></div>
        <div  id="surveyResult" style="display:none"></div>
        {!!Html::script('assets/backend/js/surveyjs/survey_review_run.js')!!}  
@endsection