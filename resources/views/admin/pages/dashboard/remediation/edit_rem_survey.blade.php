@extends('layouts.backend')

<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"
rel="Stylesheet"type="text/css"/>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
{{-- <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/eggplant/jquery-ui.min.css">
{!!Html::style('assets/backend/css/BuroRaDer.DateRangePicker.css')!!} --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">	    	
	    <div class="col-md-12">	 
	       	@php 
	       		//dd($questionsOptionsArray);
	       	@endphp
	       	<form method="post" action="{{url('remediation-updated')}}">
	       		@csrf

		    	@foreach($questionsOptionsArray as $list)
		    	<input type="hidden" name="response_id" value="{{$list->id}}">
		    	<input type="hidden" name="option_id" value="{{$list->option_id}}">
		    	<input type="hidden" name="survey_id" value="{{$list->survey_id}}">
		    		<div class="maincontainer mb-3">
		    			<h4>{{$list->questionsView->question_name}}</h4>
		    		</div>
			    	<div class="form-box">
			    		<div class="card mb-4">
							<h5 class="card-header">Please provide the following for {{$list->optionDetails->question_options}}: </h5>
							
							<div class="card-body">
							    <div class="form-inline mb-4">
							      	<label for="purchase">Cost to purchase:
							      		<input type="text" class="form-control ml-2 optionslist" id="purchase"  name="purchase" value="{{$list->purchase}}" >
							 			{{Config::get('currency.symbol.euro')}}
							  		</label>
								</div>
							    <div class="form-inline mb-4">
								    <label for="install">Cost to install:
								      	<input type="text" class="form-control ml-2" id="install" name="install" value="{{$list->install}}" value="{{$list->install}}" >
								    	{{Config::get('currency.symbol.euro')}}
								    </label>
							    </div>
							    @php
							     //$selectedvalue = $list->period_plan;
							     @endphp
							    <div class="form-inline mb-4">
							      	<label for="running">Running costs:
							      		<input type="text" class="form-control ml-2" id="running" name="running" value="{{$list->running}}" > {{Config::get('currency.symbol.euro')}}
							      		<select name="period_plan" id="period_plan" class="period_plan custom-select ml-3">
							      			
							            	<option value="per year" <?php if ('per year'== $list->period_plan) echo ' selected="selected"'?>>per year</option>
							            	<option value="per month" <?php if ('per month'== $list->period_plan) echo ' selected="selected"'?>>per month</option>
							        </select>
							        </label>
							    </div>
							    @php
							    	$date1 = date("d/m/Y", strtotime($list->startdate));
							    	$date2 = date("d/m/Y", strtotime($list->enddate));
							    @endphp
							    <div class="form-inline mb-4">
							      	<label for="startdate">Start date:
							      		<input type="text" format="dd/MM/yyyy" class="form-control ml-2 date" id="startdate" name="startdate" value="{{$date1}}">
							      	</label>
							    </div>
							    <div class="form-inline mb-4">
							      	<label for="enddate">Completion date:
							      		<input type="text" format="dd/MM/yyyy" class="form-control ml-2 date" id="enddate" name="enddate" value="{{$date2}}" >

							      	</label>
							    </div>	
							</div>
						</div>
					</div>
		    	@endforeach
		    	<div class="col-md-12">
		             <input type="submit" id="editinitial" class="btn btn-info" value="Update" id="{{$questionsOptionsArray['0']->question_id}}"  />  
		        </div>
	       	</form>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.1.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
{{-- 	{!!Html::script('assets/backend/js/BuroRaDer.DateRangePicker.js')!!} --}}
	{!!Html::script('assets/backend/js/editremsurvey.js')!!}
@endsection