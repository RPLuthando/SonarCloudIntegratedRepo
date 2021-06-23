@extends('layouts.responsible_backend')

@section('content')



<section class="pb-5">

	<div class="container-fluid mt-4">


		<div class="panel-box">
			<div class="pay-credit-title">
				<h3>{{ __('lang.review')}}: {{$entityName->name}}, {{$surveyDetailJson->name}}</h3>
			</div>

  			<div class="credit-table">
				<table class="table table-bordered">
					<tr>
						<td>Period: June 20 - August 20</td>
						<td>Questions: {{$dataCount}}</td>
					</tr>
					<tr>
					<!-- BOM SCORE -->
						@php 
						
							$score_get = number_format($sum);
							$total_score_get = number_format($total_score);
							$total_score_updated = number_format($total_updated_score);
							
						@endphp
						@if($data->revision_number > 0)
						<td class="reviewed_Data"><div class="current_score">Current score: &nbsp;<p>{{$total_score_updated}}/{{$total_score_get}}</p></div></td>
						@else
						<td class="reviewed_Data"><div class="current_score">Current score: &nbsp;<p>{{$score_get}}/{{$total_score_get}}</p></div></td>
						@endif
						<td>{{ __('lang.deadline')}} : {{$deadline}}</td>
					</tr>
				</table>
  			</div>
		</div>
  


		<form id="devel-generate-content-form">
			<div class="table-content mt-4">

			
				<div id="accordion" class="accordion">
					<div class="card mb-0 tablecard">
						<div class="card-header collapsed" data-toggle="collapse" href="#collapsethree">
							<a class="card-title">
							Select responses to update:
							</a>
						</div>
						<div id="collapsethree" class="card-body collapse show" data-parent="#accordion" >
							<div>
			
								<div class="select_all_com">
									<div class="mb-2 com_title">
										<p>Select all:</p>
									</div>
									<div class="all_box_select">
										<div  class="border-gradient border-gradient-purple mr-2">
											<a  type="button" id="noCompId" data_comp="1" onClick="noComp(this)" >NON COMPLIANT ({{$nonStandardCount}})</a>
										</div>
										<!--<div  class="border-gradient2 border-gradient-purple2 mr-2">
											<a  type="button" id="noIdealId" data-original-title='un-check' data_attr="1" onClick="noIdeal(this)" >NON IDEAL </a>
										</div>-->
									</div>
								</div>
								<table id="Responses_update" class="display cell-border datatable_content" cellspacing="0" width="100%">
									<thead>
											<tr>
												<th class="reviewed_data_checkbox">
													<div><input type="checkbox" onClick="toggle(this)" name="showhide"/>
													<label for="styled-checkbox-2"></label></div>
												</th>
												<th>Section</th>
												<th>Question</th>
												<th style="width: 30px">Comment</th>
												<th>Score</th>
												<th class="text-center">Potential</th>
												<th class="text-center">Compliance</th>
											
											</tr>
									</thead>
	
									<tbody>
									
									@foreach($dataCreatedJson as $key=>$dataget)
									@endforeach
									@if($data->revision_number == 0 || $data->revision_number == null || !isset($data->revision_number))
										@php
                                        	$i = 1;
											
                                        @endphp
										
										@if(is_array($dataCreatedJson))
										
											@foreach($dataCreatedJson as $key=>$dataget)
											
												
												<tr>
													<td class="reviewed_data_checkbox">
														<input type="checkbox" class="boxes" name="prog" id="styled-checkbox-@php echo $i; @endphp" value="{{$dataget['name']}}" >
														<label for="styled-checkbox-@php echo $i; @endphp"></label>
													</td>
													@if(!empty($dataget['section']))
														<td class="reviewed_Data"><div><p>{{$dataget['section']}}</p></div></td>
														@else
														<td></td>
													@endif
													
													@if(!empty($dataget['question']) || !empty($dataget['answered']))
														<td> 
															{{$dataget['question']}}
															<br>
														@if(!empty($dataget['answered']) || $dataget['answered'] == 0)
															>
															{{$dataget['answered']}}
														@endif
														</td>
													@else
														<td></td>	
													@endif
													
													@if(!empty($dataget['comment'])) 
														<td class="reviewed_Data" style="width: 30%"><div class="comment">{{$dataget['comment']}}</div></td>
														@else
														<td></td>		
													@endif


													
														@if((!empty($dataget['score'])) || ($dataget['score'] == '0') && $dataget['type'] != 'text')
															<td class="reviewed_Data score_data" style="text-align: center"><div>{{$dataget['score']}}</div></td>
															@elseif($dataget['type'] == 'text')
																<td class ="score_data" style="text-align: center">-</td>
															@else	
																<td></td>
														@endif
													
													
													
														@if((!empty($dataget['max']) || $dataget['max'] == '0')  && $dataget['type'] != 'text')
															<td class="reviewed_Data score_data" style="text-align: center"><div>{{$dataget['max'] - $dataget['score'] }}</div></td>
															@else
															<td class ="score_data" style="text-align: center">-</td>
														@endif

 
													@if(!empty($dataget['stand'] === 'Ideal') || !empty($dataget['stand'] === 'Optimized'))
														<td class="cmpliance_status" style="color: #2da377">{{$dataget['standard']}}</td>

													@elseif(!empty($dataget['stand'] === 'Acceptable') || !empty($dataget['stand'] === 'Managed') || !empty($dataget['stand'] === 'Defined'))
														<td class="cmpliance_status" style="color: #000000">{{$dataget['standard']}}</td>

													@elseif(!empty($dataget['stand'] === 'Non-Standard') || !empty($dataget['stand'] === 'Emerging') || !empty($dataget['stand'] === 'Initial'))
														<td class="cmpliance_status" style="color: #ef5454">{{$dataget['standard']}}</td>
													@else
														<td class ="score_data" style="text-align: center">-</td>
													@endif
													
													
												</tr>
												@php
                                        		 $i++;
                                        		@endphp
											@endforeach
										@endif
										
										<!-- BOM -->
									@elseif($data->revision_number > 0)
											@php
                                        	$i = 1;
								
                                        	@endphp
											@foreach($array_for_new_scores as $array_for_new_scores_value)
											
											<tr>
													<td class="reviewed_data_checkbox">
														<input type="checkbox" class="boxes" name="prog" id="styled-checkbox-@php echo $i; @endphp" value="{{$array_for_new_scores_value['survey_name']}}" >
														<label for="styled-checkbox-@php echo $i; @endphp"></label>
													</td>
													@if(!empty($array_for_new_scores_value['section']))
														<td class="reviewed_Data"><div><p>{{$array_for_new_scores_value['section']}}</p></div></td>
														@else
														<td></td>
													@endif
													
													@if(!empty($array_for_new_scores_value['title']))
														<td> 
															{{$array_for_new_scores_value['title']}}
															<br>
														@if(!empty($array_for_new_scores_value['answer']) || $array_for_new_scores_value['answer'] == 0)
															>
															{{$array_for_new_scores_value['answer']}}
														@endif
														</td>
													@else
														<td></td>	
													@endif
													
													@if(!empty($array_for_new_scores_value['comment'])) 
														<td class="reviewed_Data" style="width: 30%"><div class="comment">{{$array_for_new_scores_value['comment']}}</div></td>
														@else
														<td></td>		
													@endif


													
														@if(!empty($array_for_new_scores_value['newest_score']) || $array_for_new_scores_value['newest_score'] == '0')
															<td class="reviewed_Data score_data" style="text-align: center"><div>{{number_format($array_for_new_scores_value['newest_score'],2)}}</div></td>
															@else	
																<td></td>
														@endif
													
													<!-- BOM POTENTIAL SCORE -->
													
														@if(!empty($array_for_new_scores_value['max_score']) || $array_for_new_scores_value['max_score'] == '0')
														@php
                                						$review_potential_score = $array_for_new_scores_value['max_score'] - $array_for_new_scores_value['newest_score']
                                						@endphp
															<td class="reviewed_Data score_data" style="text-align: center"><div>{{number_format($review_potential_score,2)}}</div></td>
															@else
															<td class ="score_data" style="text-align: center">-</td>
														@endif	
													
													<!-- BOM COMPLIENCE -->
													@if(!empty($array_for_new_scores_value['standard']) && ($array_for_new_scores_value['standard'] == 'Ideal' || $array_for_new_scores_value['standard'] == 'Optimized'))
														<td class="cmpliance_status" style="color: #2da377">{{$array_for_new_scores_value['standard']}}</td>
																																						
													@elseif(!empty($array_for_new_scores_value['standard']) && ($array_for_new_scores_value['standard'] == 'Acceptable' || $array_for_new_scores_value['standard'] == 'Managed' || $array_for_new_scores_value['standard'] == 'Defined' || $array_for_new_scores_value['standard'] == 'Ideal'))
													<td class="cmpliance_status" style="color: #000000">{{$array_for_new_scores_value['standard']}}</td>

													@elseif(!empty($array_for_new_scores_value['standard']) && ($array_for_new_scores_value['standard'] == 'Non-Standard' || $array_for_new_scores_value['standard'] == 'Emerging' || $array_for_new_scores_value['standard'] == 'Initial'))
													<td class="cmpliance_status" style="color: #ef5454">{{$array_for_new_scores_value['standard']}}</td>
														@else
														<td class ="score_data" style="text-align: center">-</td>
													@endif
													
													
												</tr>
												@php
                                        		 $i++;
                                        		@endphp
											@endforeach			
											@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-4">
               <div class="col-md-4">
                 <div class="saveNo_btn reprot_title">
                  <p>You have chosen (<span class="count-checked-checkboxes">0</span>) questions to update</p>
                  <button  class="border-gradient border-gradient-purple btn_click_second" type="button">
                Review (<span class="count-checked-checkboxes">0</span>) Items
               </button>
               </div>
             </div>


					<div class="col-md-4">
						<div class="saveNo_btn reprot_title">
							<p>If nothing has changed, you can report no change</p>
							<a href="{{ route('getnochange',['id' => $dataget['survey_id'] , 'entityId' => $entityId] ) }}" class="border-gradient border-gradient-purple">
								Report no changes
							</a>
						</div>
					</div>
				</div>
				
			</div>
		</form>	
 	</div>
</section>

<form action="{{ route('getdatatorun') }}" id="surveyForm" method="get">
		<input type="hidden" class="survey-id-response" name="id">
		<input type="hidden" class="entity-id-response" name="en">
		
</form>

<script language="JavaScript">
			function toggle(source) {
			
				let checkboxes = document.querySelectorAll('td input[type="checkbox"]');
				for (let i = 0; i < checkboxes.length; i++) {
					if (checkboxes[i] != source)
						checkboxes[i].checked = source.checked;
						
				}
			
			}

			function noIdeal(source) {

					console.log(source);
					let checkNoIdeal = $('#noIdealId').attr('data_attr');					
					let passedArray = <?php echo '["' . implode('", "', $getIdsForNoideal) . '"]' ?>;
					let checkNewBoxes = document.getElementsByName('prog');
					document.getElementsByName("showhide")[0].checked=false;

					for (let i = 0; i < checkNewBoxes.length; i++) {
						
						let checkid = checkNewBoxes[i].id;
						if(checkNewBoxes[i].checked == true){
							checkNewBoxes[i].checked = false;
						}
						
					}

					
				$.each(passedArray, function(i, val){
					
					let checkBoxeNoIdeal = $("input[value='" + val + "']");	 
						
					
					if(checkBoxeNoIdeal.prop("checked")===true){
						checkBoxeNoIdeal.prop("checked", false);
					}else if(checkBoxeNoIdeal.prop("checked")===false){
						if(checkNoIdeal==1){
							checkBoxeNoIdeal.prop("checked", true);
							
							let checkNoIdeal = $('#noIdealId').attr('data_attr','2');
						}else{
							
							let checkNoIdeal = $('#noIdealId').attr('data_attr','1');
							checkBoxeNoIdeal.prop("checked", false);
						}
						
					}

					if ($('input.boxes').is(':checked')) {
							$('.count-checked-checkboxes').text(passedArray.length);
						
						}else{
							$('.count-checked-checkboxes').text(0);
						}

				});

			}

			function noComp(source){

					let compliance = $('#noCompId').attr('data_comp');
					let compArray = <?php echo '["' . implode('", "', $getNoComplience) . '"]' ?>;				
				
					console.log(compArray);
					let checkboxes = $('#devel-generate-content-form input[type="checkbox"]');				
					let checkboxTd = $('td input[type="checkbox"]');
					let checkNewBoxes = document.getElementsByName('prog');
					document.getElementsByName("showhide")[0].checked=false;

					for (let i = 0; i < checkNewBoxes.length; i++) {
						
						let checkid = checkNewBoxes[i].id;
						if(checkNewBoxes[i].checked == true){
							checkNewBoxes[i].checked = false;
						}
						
					}
					
				$.each(compArray, function(i, val){
					let checkBoxeNoComp = $("input[value='" + val + "']");
				
					
					if(checkBoxeNoComp.prop("checked")===true){
						checkBoxeNoComp.prop("checked", false);
					}else if(checkBoxeNoComp.prop("checked")===false){
						if(compliance == 1){
							checkBoxeNoComp.prop("checked", true);

							let compliance = $('#noCompId').attr('data_comp','2');
						}else{

							let compliance = $('#noCompId').attr('data_comp','1');
							checkBoxeNoComp.prop("checked", false);
						}
						
					}

						if ($('input.boxes').is(':checked')) {
							$('.count-checked-checkboxes').text(compArray.length);
					
					}else{
						$('.count-checked-checkboxes').text(0);
					}
							
				});
			}
	</script>

<!--

 Create survey and redirect to checkingController

 -->

	<script type="text/javascript">
	
				$(document).ready(function() {
					let dialog = document.getElementById('myFirstDialog'); 
					$(".btn_click_second").click(function(){ 
					let checkboxValues = [];
					
					$.each($("input[name='prog']:checked"), function(){            
							checkboxValues .push($(this).val()); 
							 
						});

						console.log(checkboxValues);
						
						
						$.ajax({
            
							type: "GET",
							url: "/allsurveychecked/",
							data: {custom : checkboxValues, survey_id : "{{ $dataget['survey_id'] }}", entity_id :  "{{$entityId}}"},

							//data: {custom : checkboxValues, survey_id : "{{ $array_for_new_scores_value['survey_id'] }}", entity_id :  "{{$entityId}}"},
							cache: false,
							success: function(values){ 
						
								$('.survey-id-response').val(values.survey_id)
								$('.entity-id-response').val(values.entity_id)
								$('#surveyForm').submit();
	

							}
						});
					});
					   
				});
	</script>

	<script>
		$(document).ready(function(){

			let $checkboxes = $('#devel-generate-content-form input[type="checkbox"]');
			let $singleCheckboxes = $('#devel-generate-content-form td input[type="checkbox"]');

			
			$checkboxes.change(function(){
				
				let countCheckedCheckboxes = $checkboxes.filter(':checked').length;
				let differance = $singleCheckboxes.filter(':checked').length;
				console.log(differance);
				console.log(countCheckedCheckboxes);
				$('.count-checked-checkboxes').text(differance);
			});

			});
	</script>

@endsection
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>