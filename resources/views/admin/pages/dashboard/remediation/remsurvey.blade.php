@extends('layouts.backend')
	<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"
	rel="Stylesheet"type="text/css"/>
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	{{-- <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/eggplant/jquery-ui.min.css"> --}}
	{!!Html::style('assets/backend/css/BuroRaDer.DateRangePicker.css')!!}
@section('content')
<div class="container">
    <div class="row justify-content-center">	    	
	    <div class="col-md-12">	 
	        <form id="myForm" method="post" action="{{url('/rem-survey-step')}}">
	        	@csrf
	        	 <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
	        	 <input type="hidden" id="total_questions_count" value="{{$questionsOptionsArray->count()}}" name="total_questions">
	        	  
		        <div>
		        	@php 
		        		$j=0; 
		        	@endphp 
		        	@foreach($questionsOptionsArray as $survey_question_name)
		        	
		        	<input type="hidden" id="survey_id" value="{{$survey_question_name->question_cat_id}}" name="survey_id[]">
						<div class="maincontainer mb-3 field-set{{$loop->iteration}}" id="field-set{{$loop->iteration}}" style="display:none;">
							<div class="list-view">
								<h4>{{$survey_question_name->question_name}}</h4>
			        	     	<ul class="list-unstyled">
			        	     		@php 
			        	     			$prevState = ''; 
			        	     		@endphp
			        	     		@foreach( $survey_question_name->questionoption as $optionsList)
			        	     			
				        	     		@if( $optionsList->ideal_standard=="Yes")
				        	     			<li>
				        	     				@php 
				        	     					$stringIdeal= 'The ideal standard is:';

				        	     				@endphp
				        	     				@if($prevState != $stringIdeal)
				        	     					@php 
					        	     					 $prevState = $stringIdeal;
					        	     				@endphp
				        	     					{{$stringIdeal}}
				        	     				@endif 
				        	     				<b>{{$optionsList->question_options}}</b>
				        	     				<i class="fa fa-star" aria-hidden="true"></i>
				        	     			</li>
				        	     		@elseif($optionsList->ideal_standard == "No" && $optionsList->acceptable_standard == "Yes" )
				        	     			
				        	     				@if (in_array($optionsList->question_options, $optionsName)) 
													@else
													<li> 
							        	     				@php 
							        	     					$string= 'The acceptable standard is:';
							        	     				@endphp
							        	     				@if($prevState != $string)
								        	     				@php 
								        	     					 $prevState = $string;
								        	     				@endphp
							        	     					{{$string}}
							        	     				@endif
							        	     				
							        	     					<b>{{$optionsList->question_options}} </b>
							        	     					<i class="fa fa-star-half-o" aria-hidden="true"></i>
							        	     			</li>
												@endif
				        	     		@endif
			        	     		@endforeach
			        	     	</ul>
			        	    </div>
			        	    <div class="form-box">
			        	    	@php $a=0; @endphp
			        	     	@foreach( $survey_question_name->questionoption as $optionsList)

			        	     		@if(!($optionsList->ideal_standard == 'No' && $optionsList->acceptable_standard == 'No' ) && $optionsList->score_current <= 1 )

					        	   		@if (in_array($optionsList->question_options, $optionsName)) 
										@else	
											<div class="card mb-4">												
												<h5 class="card-header">Please provide the following for  
											{{$optionsList->question_options}} : </h5>
												
												<input type="hidden" name="optionsData[{{$j}}][]" value="{{$optionsList->id}}">
												<input type="hidden" name="questionData[{{$j}}][]" value="{{$optionsList->question_id}}">
												
												<div class="card-body">
												    <div class="form-inline mb-4">
												      	<label for="purchase{{$a}}{{$j}}{{$optionsList->question_id}}">Cost to purchase:*
												      		<input type="text" class="valid numeric form-control ml-2 optionsList" id="purchase_{{$a}}{{$j}}{{$optionsList->question_id}}"  name="purchase[{{$j}}][]" required="required">
												 			{{Config::get('currency.symbol.euro')}}
												  		</label>
												  		<div><span id="errorpurchase{{$a}}" style="display:none;">Please fill the required field</span></div>
													</div>
												    <div class="form-inline mb-4">
													    <label for="install{{$a}}{{$j}}{{$optionsList->question_id}}">Cost to install:*
													      	<input type="text" class="valid numeric form-control ml-2" id="install_{{$a}}{{$j}}{{$optionsList->question_id}}" name="install[{{$j}}][]" required="required">
													    	{{Config::get('currency.symbol.euro')}}
													    </label>
													    <div><span id="errorinstall{{$a}}" style="display:none;">Please fill the required field</span></div> 
													    
												    </div>
												    <div class="form-inline mb-4">
												      	<label for="running{{$a}}{{$j}}{{$optionsList->question_id}}">Running costs:*
												      		<input type="text" class="valid numeric form-control ml-2" id="running_{{$a}}{{$j}}{{$optionsList->question_id}}" name="running[{{$j}}][]" required="required"> {{Config::get('currency.symbol.euro')}}
												      		<select name="period_plan[{{$j}}][]" id="period_plan{{$optionsList->id}}" class="period_plan custom-select ml-3">
												      			
												            	<option value="per year">per year</option>
												            	<option value="per month">per month</option>
												        </select>
												        </label>
												        <div><span id="errorrunning{{$a}}" style="display:none;">Please fill the required field</span></div>
												    </div>
												    <div class="form-inline mb-4">
												      
												      	<label for="startdate_{{$j}}{{$a}}">Start date:* </label>
														  <input type="text" class="start" placeholder="dd/mm/yy" id="startdate_{{$a}}{{$j}}{{$optionsList->question_id}}"  name="startdate[{{$j}}][]"  required="required">
														 
												    </div>
												    <div class="form-inline mb-4">
												      
												      	 <label for="enddate_{{$j}}{{$a}}">Completion date:* </label>
														  <input type="text" placeholder="dd/mm/yy" class="enddate" id="enddate_{{$a}}{{$j}}{{$optionsList->question_id}}"  name="enddate[{{$j}}][]"  required="required"> 
												    </div>	
												</div>
											</div>
											@php
												$a++;
											@endphp
										@endif
										
									@endif										
								@endforeach
								<input type="hidden" id="countOption" value="{{$optionsList->count()}}">
								 <button type="button" class="btn btn-primary next_step_rem" iterate="{{$loop->iteration}}" a="{{$a}}" j="{{$j}}" question_id="{{$optionsList->question_id}}">NEXT STEP</button>
							</div>
						</div>
						@php $j++; @endphp 
		        	@endforeach  
		        	
		        	<div class="row cls" style="display: none;">
		            	<div class="col-md-12" >
		            		  <button type="submit"  style="display: none;" id="reviewRemResponse" class="btn btn-danger">SUBMIT RESPONSE</button>
		            	</div>
	            	</div>
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
	{{-- {!!Html::script('assets/backend/js/BuroRaDer.DateRangePicker.js')!!} --}}
	{!!Html::script('assets/backend/js/remsurvey.js')!!}
@endsection