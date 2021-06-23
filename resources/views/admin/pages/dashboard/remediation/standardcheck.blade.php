@extends('layouts.backend')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<style type="text/css">
	.responses{
		display:flex;
		flex-direction: column;
	}
	.responses .red{
		order:2;
	}
	.responses .green{
		order:1;
	}
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">	    	
	    <div class="col-md-12">	 
	     	<div class="row mt-5">
	        	<div class="noresults col-md-12" style="display:none;">No results found</div>
	        	<form method="post" action="{{url('/rem-survey')}}">    
		        		@csrf
					<div class="mb-5">  
						<h2>{{$surveyOptions[0]->survey_category->question_cat_name}}(remediation)</h2> 
					</div>
		        	<div class="col-md-12 responses mb-3">
		        		@php 
		        			$i=0; 
		        			$prevState = ''; $initState=''; 
		        		@endphp
			        	@foreach($surveyOptions as $sO)		        	
			        		@if(!($sO->optionDetails->ideal_standard == 'No' ))
			        				@php 
					        	     	$stringPattern= 'Your initial survey showed that you are standard compliant on these areas:';
					        	    @endphp
					        	    @if($prevState != $stringPattern)
	        	     					@php 
		        	     					 $prevState = $stringPattern;
		        	     				@endphp
	        	     					<h4 class="green">{{$stringPattern}}</h4>
	        	     				@endif 
				        		<div class="mb-2 green"  style="border: 1px solid green;padding: 14px 14px;">
				        			<h5>{{$sO->questionsView->question_name}}</h5>
				        			
					            	@if($sO->optionDetails->question_options == 'Other')
						        		<div class="abc"> {{$sO->other}}<i class="fa fa-check" aria-hidden="true"></i></div>
						        	@elseif($sO->optionDetails->question_options !== 'Other')
						        		<div class="abc"> {{$sO->optionDetails->question_options}}<i class="fa fa-check" aria-hidden="true"></i></div>
						        	@endif
					            </div>
					        @elseif( $sO->optionDetails->ideal_standard == 'No' )
					        	@php 
						        	$stringPatterns= 'These areas require estimation in order to bring them to standard:';
					        	@endphp
				        	    @if($initState != $stringPatterns)
	    	     					@php 
	        	     					$initState = $stringPatterns;
	        	     				@endphp
	    	     					<h4 class="red mt-4">{{$stringPatterns}}</h4>
	    	     				@endif 
					         	<div class="mb-2 red" style="border: 1px solid red;padding: 14px 14px;">
						         	
					         		<input type="hidden" name="survey_question_names[]" value="{{$sO->questionsView->question_name}}">
					         		<input type="hidden" name="survey_question_id[]" value="{{$sO->questionsView->id}}">
				         			<h5>{{$sO->questionsView->question_name}}</h5>
						           
						            @if($sO->optionDetails->question_options == 'Other')
						        		<div class="abc"> {{$sO->other}}<i class="fa fa-times" aria-hidden="true"></i></div>
				    					<input type="hidden" name="survey_option_names[]" value="{{$sO->other}}">
						        	@elseif($sO->optionDetails->question_options !== 'Other')
						        		<div class="abc"> {{$sO->optionDetails->question_options}}<i class="fa fa-times" aria-hidden="true"></i></div>
				    					<input type="hidden" name="survey_option_names[]" value="{{$sO->optionDetails->question_options}}">
						        	@endif
						        </div>
					        @endif
					 		<?php  $i++;?>				
			        	@endforeach
			        </div>
			        <input type="hidden" name="survey_id" value="{{$surveyOptions[0]->survey_category->id}}">
			        <input type="submit" class="btn btn-info" value="NEXT STEP">
		       	</form>
	        </div>
	    </div>
	</div>
</div>
@endsection
