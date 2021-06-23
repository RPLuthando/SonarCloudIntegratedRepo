@extends('layouts.survey_header')

@section('contents')
    {!!Html::style('assets/backend/css/dash_style.css')!!}
    <div class="survey-page-header col-md-10">
<!--        <div class="start_btn1">
            <button onclick="window.location = '/'">Return to Dashboard</button>
        </div>-->
        <a href="/" class="btn btn-primary start_btn1">Return to Dashboard</a>
    </div>
    <head>
        <title></title> 
       
        <meta name="csrf-token" content="{{ csrf_token() }}"> 
        <meta name="viewport" content="width=device-width"/>
        <script src="https://unpkg.com/jquery"></script>
        <script src="https://unpkg.com/survey-jquery@1.8.23/survey.jquery.min.js"></script>
        <script src="https://surveyjs.azureedge.net/1.8.9/survey.jquery.min.js"></script>
        <link href="https://unpkg.com/survey-knockout@1.8.23/modern.css" type="text/css" rel="stylesheet"/>

    </head>
    
    <body>
        @csrf
        <div id="surveyElement" style="display:inline-block;width:100%;"></div>
        <div  id="surveyResult" style="display:none">UPLOAD EVIDENCE</div>
        
        {!!Html::script('assets/backend/js/surveyjs/surveyrun_new.js')!!}   
    </body>
@endsection