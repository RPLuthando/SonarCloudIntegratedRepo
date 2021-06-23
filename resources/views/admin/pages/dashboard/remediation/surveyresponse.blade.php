@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
	    
	    @php
	  //  $surveyId = fetch_questions_via_survey_id($survey_id);
	     //dd($surveyRemediationArray);
	    @endphp
	    <div class="col-md-12">	        
	        <div>
	            <h2>Please review your responses before submitting:</h2>
	        </div>
	        <div class="row mt-5">
	        	<div class="table-responsive">
	        		 <table class="table table-bordered">
	        		 	<thead>
					        <tr>
					          <th></th>
					          <th class="text-center">Purchase</th>
					          <th class="text-center">Install</th>
					          <th class="text-center">Running</th>
					          <th class="text-center">Start</th>
					          <th class="text-center">End</th>
					          <th class="text-center">Duration</th>
					        </tr>
					    </thead>
					      <tbody>
					      	@php
					      		$values  = [];
					      	@endphp 
	        			@foreach($surveyRemediationArray as $surveyValues)

	        			@php
	        				
	        				$date1 = date("d/m/Y", strtotime($surveyValues->startdate));
	        				$date2 = date("d/m/Y", strtotime($surveyValues->enddate));
	        				$diff = abs(strtotime($surveyValues->startdate) - strtotime($surveyValues->enddate));
							$years = floor($diff / (365*60*60*24));
							$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
							$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
							$questionValue = $surveyValues->questionsView->question_name;
							//printf("%d years, %d months, %d days\n", $years, $months, $days);
	        			@endphp
	        			@if(!in_array($questionValue, $values) )
			        		<tr>
			        			@php 
						        	$values[] = $questionValue;
			        				//$questionname = $values;
			        			@endphp
								<td>{{$questionValue}}</td>
						        
							</tr>
			        	@endif
						    <tr>
					          	<td class="text-center">{{$surveyValues->optionDetails->question_options}}<a href="{{url('/edit-rem-response/'.$surveyValues->option_id.'/'.$surveyValues->survey_id.'')}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
					          	<td>{{$surveyValues->purchase}}{{Config::get('currency.symbol.euro')}}</td>
						        <td>{{$surveyValues->install}}{{Config::get('currency.symbol.euro')}}</td>
						        @if($surveyValues->period_plan == 'per month')
						        	<td>{{$surveyValues->running}}{{Config::get('currency.symbol.euro')}}(pcm)</td>
						        @elseif($surveyValues->period_plan == 'per year')
						        	<td>{{$surveyValues->running}}{{Config::get('currency.symbol.euro')}}(pcy)</td>
						        @endif
						        <td>{{$date1}}</td>
						        <td>{{$date2}}</td>
						        @if($years != 0 && $months != 0)
						        	<td><?php printf("%d years, %d months, %d days\n", $years, $months, $days); ?></td>
						       	@elseif($years == 0 && $months == 0)
						       		<td><?php printf(" %d days\n", $days); ?></td>
						       	@elseif($years == 0 && $months != 0)
						       		<td><?php printf(" %d months, %d days\n", $months,$days); ?></td>
						        @endif

						    </tr>
						
					    @endforeach
					 </table>
 				</div>
	        </div>

	        <br>
	       	<div class="row">
	       		<div class="col-md-12">
	       			<a href="{{url('/remediation-completion/'.$surveyRemediationArray[0]->survey_id.'')}}" class="btn btn-primary">SUBMIT RESPONSE</a>
	       			{{-- <a href="{{url('/final-submit/'.$response->survey_id.'')}}" class="btn btn-danger">SUBMIT YOUR RESPONSE</a> --}}
	       		</div>
	       	</div>	

	    </div>
	     

	</div>
</div>
@endsection

