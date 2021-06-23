
@extends('layouts.responsible_backend') 

@section('content')
{!!Html::style('assets/backend/css/dash_style.css')!!}

@if(check_role()==1)
<div class="container">
    <div class="row">
        @include('admin.sidebar')
        <div class="col-md-9">
            <div class="card"> 
                <div class="card-header">
                    {{__('lang.dashboard')}}
                </div>
                <div class="card-body">
                    {{__('lang.your_application_dashboard')}}
                </div>
            </div>
        </div>
    </div>
</div>
@elseif(check_role()==3) 

<section class="pb-5"> 

    <div class="container-fluid">
        <div class="Dashboard-content1">
            <div class="user-title">
                <div>
                    <img src="{{ asset('img/images/user-title.png') }}">
                </div>
                <div class="user-title-text">
                    <h3>Welcome Back {{ Auth::user()->name }}</h3>
                    <p>If you have no changes to submit, select all surveys that apply in the right hand column and use the ‘submit no changes’ button</p>
                </div>
            </div>
        </div>
          


        <div class="table-content mt-4">
		    <!--aks code start -->
		    <div id="accordion" class="accordion mb-5">
                <div class="card mb-0 tablecard">
                    <div class="card-header collapsed" data-toggle="collapse" href="#collapsezero">
                        <a class="card-title">
                            Current Survey:
                        </a>
                    </div>
                    <div id="collapsezero" class="card-body collapse show" data-parent="#accordion" >
                        <div>
                            <table id="currentSur" class="display cell-border datatable_content" cellspacing="0" width="100%">
                                <thead> 
                                    <tr>
                                        <th>Entity</th>
                                        <th>Survey Name</th>
                                        <th style="width: 1%; text-align: center">Status</th>
                                        <th style="width: 1%; text-align: center">Deadline</th>
                                        <th></th> 
                                    </tr>
                                </thead>
             
                                <tbody>
                                  
                                    @foreach($entities_data as $tiles)
                                   
                                    @foreach($tiles['survey_details'] as $survey_data)
                                        @if($survey_data['group'] !== Null && $tiles['group'] === $survey_data['group'])
                                            @if($survey_data['survey_status'] != 'Complete_initial' && $survey_data['survey_status'] != 'Review_completed' && $survey_data['survey_status'] != 'Review_in_progress')

                                                <tr>
                                                    <td class="reviewed_Data"><div><p>{{$tiles['name']}}</p></div></td>
                                                    <td class="reviewed_Data"><div><p>{{$survey_data['survey_name']}}</p></div></td>
                                                    <td style="text-align: center"><div>{{$survey_data['survey_status']}}</div></td>
                                                    <td style="text-align: center"><div>{{$survey_data['deadline']}}</div></td>
                                                    <td class="start_btn1"> 
                                                    <div>
                                                    @if(($survey_data['survey_status'] == 'in_progress'))
{{--                                                    <a   href="{{ route('surveydesc',['id' => $survey_data['survey_id'] , 'entityId' => $tiles['id']] ) }}" >Continue</a> --}}
                                                            <a   href="{{ url('surveyrun?id='.$survey_data['survey_id'].'&&en='.$tiles['id']) }}" >Continue</a>
                                                    @else
{{--                                                    <a   href="{{ route('surveydesc',['id' => $survey_data['survey_id'] , 'entityId' => $tiles['id']] ) }}" >Start</a>--}}
                                                    <a   href="{{ url('surveyrun?id='.$survey_data['survey_id'].'&&en='.$tiles['id']) }}" >Start</a>
                                                    @endif
                                                    </div>         
                                                    </td>
                                                </tr> 
                                                @endif
                                            @elseif($survey_data['group'] !== Null && $tiles['group'] === 'securityandprivacy')
                                                @if($survey_data['survey_status'] != 'Complete_initial' && $survey_data['survey_status'] != 'Review_completed' && $survey_data['survey_status'] != 'Review_in_progress')

                                                <tr>
                                                    <td class="reviewed_Data"><div><p>{{$tiles['name']}}</p></div></td>
                                                    <td class="reviewed_Data"><div><p>{{$survey_data['survey_name']}}</p></div></td>
                                                    <td><div>{{$survey_data['survey_status']}}</div></td>
                                                    <td style="text-align: center"><div>{{$survey_data['deadline']}}</div></td>
                                                    <td class="start_btn1"> 
                                                    <div>
                                                    @if(($survey_data['survey_status'] == 'in_progress'))
{{--                                                    <a   href="{{ route('surveydesc',['id' => $survey_data['survey_id'] , 'entityId' => $tiles['id']] ) }}" >Continue</a> --}}
                                                            <a   href="{{ url('surveyrun?id='.$survey_data['survey_id'].'&&en='.$tiles['id']) }}" >Continue</a>
                                                    @else
{{--                                                    <a   href="{{ route('surveydesc',['id' => $survey_data['survey_id'] , 'entityId' => $tiles['id']] ) }}" >Start</a>--}}
                                                    <a   href="{{ url('surveyrun?id='.$survey_data['survey_id'].'&&en='.$tiles['id']) }}" >Start</a>
                                                    @endif
                                                    </div>         
                                                    </td>
                                                </tr> 
                                                @endif
                                            @endif
                                        @endforeach
                                    @endforeach  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
		    <!--aks code end -->
            <!--&&&&&&&&&&& BOM TO BE REVIEWED &&&&&&&&&&&-->
            <form method="post" action="{{ url('/checkedvalue') }}" >
                @csrf
                <div id="accordion" class="accordion">
                    <div class="card mb-0 tablecard">
                        <div class="card-header collapsed" data-toggle="collapse" href="#collapseOne">
                            <a class="card-title">
                                To be reviewed:
                            </a>
                        </div> 
                
                        <div id="collapseOne" class="card-body collapse show" data-parent="#accordion">
                            <div>
                                <table id="reviewedtable" class="display cell-border datatable_content" cellspacing="0" width="100%">
                                    <thead>
                                        <tr> 
                                            <th>Entity</th>
                                            <th>Survey Name</th>
                                            <th style="width: 1%; text-align: center">Deadline</th>
                                            <th style="width: 1%; text-align: center">Score</th>
                                            <th style="width: 1%;"></th>
                                            <th style="width: 10%; text-align: center">No change</th>
                                        </tr>
                                    </thead>
                                    
                                        @php
                                        $i = 1;
                                        @endphp
                                        <!-- BOM START HANDLING GROUP-->
                                        
                                            @foreach($entities_data as $tiles_comp)
                                            @foreach($tiles_comp['survey_details'] as $survey_data)
                                            @if($survey_data['group'] !== Null && $tiles['group'] === $survey_data['group'])
                                                @if((($survey_data['survey_status'] === 'Complete_initial') or ($survey_data['survey_status'] === 'Review_in_progress'))) 
                                                   @php 
                                                    if(!empty($survey_data['survey_Score']) || !empty($survey_data['totalScore']) || !empty($survey_data['sjs_score_total_score'])){
                                                        $survey_Score = number_format($survey_data['survey_Score']);
                                                        $totalScore = number_format($survey_data['totalScore']);
                                                        $sjs_total = number_format($survey_data['sjs_score_total_score']);
                                                    }
                                                    
                                                   @endphp
                                                <tbody>
                                                <!--Hide on master unhidden on develop--> 
                                                   <?php if($survey_data['survey_status'] == 'Complete_initial' || $survey_data['survey_id'] === 142) { ?>
                                                    
                                                    <?php } else { ?>
                                                    <tr>
                                                        <td class="reviewed_Data"><div><p>{{$tiles_comp['name']}} </p></div></td>
                                                        <td class="reviewed_Data"><div><p>{{$survey_data['survey_name']}}</p></div></td>
                                                        <td style="text-align: center"><div>{{$survey_data['deadline']}}</div></td>


                                                        <!-- BOM SCORE HANDLING-->
                                                        @if(isset($survey_data['revision_nr']))
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">{{$sjs_total}}/{{$totalScore}}</p></div></td>
                                                        @elseif(!empty($survey_data['survey_Score']) && !empty($survey_data['totalScore']))
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">{{$survey_Score}}/{{$totalScore}}</p></div></td>
                                                        @elseif(!empty($survey_data['totalScore']))
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">0/{{$totalScore}}</p></div></td>
                                                        @else
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">0</p></div></td>
                                                        @endif
                                                        <td>
                                                        <!-- BOM SCORE HANDLING-->   
                                                                @php
                                                                    $id = $survey_data['survey_id'];
                                                                    $entityId = $tiles_comp['id'];
                                                                @endphp
                                                                @if(($survey_data['survey_status'] == 'Review_in_progress'))
                                                                <div class="start_btn2">
                                                                
                                                                <a  href="{{ url('getdatatorun?id='.$id.'&&en='.$entityId) }}" >Continue</a>
                                                                </div>                                   
                                                                @elseif($survey_data['survey_status'] == 'Complete_initial')
                                                               <div class="start_btn1">
                                                                <a class="start_btn1" href="{{ route('surveytestnew',['id' => $survey_data['survey_id'] , 'entityId' => $tiles_comp['id']] ) }}" >Start</a>                        
                                                                
                                                                </div>
                                                                  
                                                                @endif
                                                            
                                                        </td>
                                                             
                                                            <td class="reviewed_data_checkbox">
                                                                <div>  
                                                                @if(($survey_data['survey_status'] != 'Review_in_progress') && ($survey_data['survey_status'] == 'Complete_initial'))
                                                                                        
                                                                    <input class="styled-checkbox" id="styled-checkbox-@php echo $i; @endphp" name="columnId[]" type="checkbox" value={{$survey_data['result_id']}}>
                                                                    <label for="styled-checkbox-@php echo $i; @endphp"></label>
                                                                @endif    
                                                                </div>
                                                            </td>
                                                    </tr> 
                                                    <?php } ?>
                                                </tbody>
                                                @endif
                                                @php
                                                $i++;
                                                @endphp
                                        <!-- BOM END HANDLING GROUP -->
                                                @elseif($survey_data['group'] !== Null && $tiles['group'] === 'securityandprivacy')
                                                @if((($survey_data['survey_status'] === 'Complete_initial') or ($survey_data['survey_status'] === 'Review_in_progress'))) 
                                                   @php 
                                                    if(!empty($survey_data['survey_Score']) || !empty($survey_data['totalScore']) || !empty($survey_data['sjs_score_total_score'])){
                                                        $survey_Score = number_format($survey_data['survey_Score']);
                                                        $totalScore = number_format($survey_data['totalScore']);
                                                        $sjs_total = number_format($survey_data['sjs_score_total_score']);
                                                    }
                                                    
                                                   @endphp
                                                   <?php //echo $survey_Score . ' ' . $totalScore . '<br>';?>
                                                   <tbody>
                                                    <tr>
                                                        <td class="reviewed_Data"><div><p>{{$tiles_comp['name']}}</p></div></td>
                                                        <td class="reviewed_Data"><div><p>{{$survey_data['survey_name']}}</p></div></td>
                                                        
                                                        <td style="text-align: center"><div>{{$survey_data['deadline']}}</div></td>
                                                        @if(isset($survey_data['revision_nr']))
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">{{$sjs_total}}/{{$totalScore}}</p></div></td>
                                                        @elseif(!empty($survey_data['survey_Score']) && !empty($survey_data['totalScore']))
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">{{$survey_Score}}/{{$totalScore}}</p></div></td>
                                                        @elseif(!empty($survey_data['totalScore']))
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">0/{{$totalScore}}</p></div></td>
                                                        @else
                                                        <td class="reviewed_Data" style="text-align: center"><div><p style="margin: auto;">0</p></div></td>
                                                        @endif
                                                        <td>
                                                            
                                                                @php
                                                                    $id = $survey_data['survey_id'];
                                                                    $entityId = $tiles_comp['id'];
                                                                @endphp
                                                                @if(($survey_data['survey_status'] == 'Review_in_progress'))
                                                                <div class="start_btn2">
                                                                
                                                                <a  href="{{ url('getdatatorun?id='.$id.'&&en='.$entityId) }}" >Continue</a>
                                                                </div>                                   
                                                                @elseif($survey_data['survey_status'] == 'Complete_initial')
                                                               <div class="start_btn1">
                                                                <a class="start_btn1" href="{{ route('surveytestnew',['id' => $survey_data['survey_id'] , 'entityId' => $tiles_comp['id']] ) }}" >Start</a>                        
                                                                
                                                                </div>
                                                                  
                                                                @endif
                                                            
                                                        </td>
                                                             
                                                            <td class="reviewed_data_checkbox">
                                                                <div>  
                                                                @if(($survey_data['survey_status'] != 'Review_in_progress') && ($survey_data['survey_status'] == 'Complete_initial'))
                                                                                        
                                                                    <input class="styled-checkbox" id="styled-checkbox-@php echo $i; @endphp" name="columnId[]" type="checkbox" value={{$survey_data['result_id']}}>
                                                                    <label for="styled-checkbox-@php echo $i; @endphp"></label>
                                                                @endif    
                                                                </div>
                                                            </td>
                                                    </tr> 
                                                @endif
                                                @php
                                                $i++;
                                                @endphp
                                                @endif
                                            @endforeach     
                                            @endforeach 
                                        
                                    <!-- BOM -->
                                    </tbody>
                                    <!-- BOM -->
                                  
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="saveNo_btn text-right mt-3">
                    <!-- <a class="border-gradient border-gradient-purple" type="submit"> -->
                    <button  type="submit" class="border-gradient border-gradient-purple">
                            Submit no changes
                    </button>
                    <!-- </a> -->
                </div>
            </form>
            <!--&&&&&&&&&&& BOM END OF TO BE REVIEWED &&&&&&&&&&&-->
          
            <div id="accordion" class="accordion mt-5">
                <div class="card mb-0 tablecard">
                    <div class="card-header collapsed" data-toggle="collapse" href="#collapsetow">
                        <a class="card-title">
                            Completed:
                        </a>
                    </div>
                    <div id="collapsetow" class="card-body collapse show" data-parent="#accordion" >
                        <div>
                            <table id="Completedtable" class="display cell-border datatable_content" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Entity</th>
                                        <th>Survey Name</th> 
                                        <th style="width: 1%; text-align: center">Submitted</th>
                                        <th style="width: 1%; text-align: center">Score</th>
                                        <th style="width: 1%; text-align: center">Change</th>
                                        <th></th>
                                       
                                
                                    </tr>
                                </thead>
                
                                <tbody>
                                    <!-- BOM -->
                                    @foreach($entities_data as $tiles_comp ) 
                                        @foreach($tiles_comp['survey_details'] as $survey_data)
                                        @if($survey_data['group'] !== Null && $tiles['group'] === $survey_data['group'])
                                            @if($survey_data['survey_status'] === 'Review_completed') 

                                            
                                                <tr>
                                            
                                                
                                                    <td class="completed_Data"><div><p>{{$tiles_comp['name']}}</p></div></td>
                                                    <td class="completed_Data" style="text-align: center"><div><p>{{$survey_data['survey_name']}}</p></div></td>
                                                    <td style="text-align: center"><div>{{$survey_data['update_complete']}}</div></td>

                                                    @if($survey_data['revision_nr'] == 0 || $survey_data['revision_nr'] == null)
                                                    <td style="text-align: center"><div>{{number_format($survey_data['survey_Score'])}}</div></td>

                                                    @elseif($survey_data['revision_nr'] > 0)
                                                    
                                                        @if($survey_data['old_score'] < $survey_data['change_score'])
                                                        @php
                                                        $change_1 = $survey_data['sjs_score_total_score'] + ($survey_data['change_score'] - $survey_data['old_score'])
                                                        @endphp
                                                        <td style="text-align: center"><div>{{number_format($change_1)}}</div></td>
                                                        @elseif($survey_data['old_score'] > $survey_data['change_score'])
                                                        @php
                                                        $change_2 = $survey_data['sjs_score_total_score'] - ($survey_data['old_score'] - $survey_data['change_score'])
                                                        @endphp
                                                        <td style="text-align: center"><div>{{number_format($change_2)}}</div></td>
                                                        @elseif($survey_data['old_score'] == $survey_data['change_score'])
                                                        <td style="text-align: center"><div>{{number_format($survey_data['sjs_score_total_score'])}}</div></td>   
                                                        @endif                                             
                                                    @endif

                                                    @if(!empty($survey_data['old_score']) && !empty($survey_data['change_score']))
                                                        @if(($survey_data['old_score']) == ($survey_data['change_score']))
                                                        <td class="change_td_data"><div>0</div></td>
                                                        @elseif(($survey_data['old_score']) > ($survey_data['change_score']))
                                                        @php 
                                                            $greater_old_score = $survey_data['old_score'] - $survey_data['change_score']; 
                                                            $greater_old_score_round = number_format($greater_old_score);
                                                        @endphp
                                                        <td class="change_td_data"><div>-{{$greater_old_score_round}}</div></td>
                                                        @elseif(($survey_data['old_score']) < ($survey_data['change_score']))
                                                        @php 
                                                            $greater_change_score = $survey_data['change_score'] - $survey_data['old_score']; 
                                                            $greater_change_score_round = number_format($greater_change_score);
                                                        @endphp
                                                        <td class="change_td_data"><div>+{{$greater_change_score_round}}</div></td> <!-- -->
                                                    
                                                        @endif
                                                    @else
                                                        @if(($survey_data['change_score'] == 0) && ($survey_data['old_score'] == 0))
                                                        
                                                        <td class="change_td_data"><div>0</div></td>
                                                        @elseif($survey_data['change_score'] == 0)
                                                        @php 
                                                            $old_score = number_format($survey_data['old_score']); 
                                                           
                                                        @endphp
                                                            <td class="change_td_data"><div>-{{$old_score}}</div></td>
                                                        @elseif($survey_data['old_score'] == 0)
                                                        @php 
                                                           
                                                            $change_score = number_format($survey_data['change_score']);
                                                        @endphp
                                                        <td class="change_td_data"><div>+{{$change_score}}</div></td>
                                                        @else
                                                        <td class="change_td_data"><div>0</div></td>
                                                        @endif
                                                    @endif                          
                                                    <td class="start_btn3"><div><a href="{{ url('viewsubmission?id='.$survey_data['survey_id'].'&&en='.$tiles_comp['id']) }}">view submission</a></div></td>                            
                                                   
                                                </tr>  
                                            @endif
                                            @elseif($survey_data['group'] !== Null && $tiles['group'] === 'securityandprivacy')
                                            @if($survey_data['survey_status'] === 'Review_completed') 

                                            
                                                <tr>
                                            
                                                
                                                    <td class="completed_Data"><div><p>{{$tiles_comp['name']}}</p></div></td>
                                                    <td class="completed_Data"><div><p>{{$survey_data['survey_name']}}</p></div></td>
                                                    <td><div>{{$survey_data['update_complete']}}</div></td>
                                                    
                                                    @if(!empty($survey_data['old_score']) && !empty($survey_data['change_score']))
                                                        @if(($survey_data['old_score']) == ($survey_data['change_score']))
                                                        <td class="change_td_data"><div>0</div></td>
                                                        @elseif(($survey_data['old_score']) > ($survey_data['change_score']))
                                                        @php 
                                                            $greater_old_score = $survey_data['old_score'] - $survey_data['change_score']; 
                                                            $greater_old_score_round = number_format($greater_old_score);
                                                        @endphp
                                                        <td class="change_td_data"><div>-{{$greater_old_score_round}}</div></td>
                                                        @elseif(($survey_data['old_score']) < ($survey_data['change_score']))
                                                        @php 
                                                            $greater_change_score = $survey_data['change_score'] - $survey_data['old_score']; 
                                                            $greater_change_score_round = number_format($greater_change_score);
                                                        @endphp
                                                        <td class="change_td_data"><div>+{{$greater_change_score_round}}</div></td>
                                                    
                                                        @endif
                                                    @else
                                                        @if(($survey_data['change_score'] == 0) && ($survey_data['old_score'] == 0))
                                                        
                                                        <td class="change_td_data"><div>0</div></td>
                                                        @elseif($survey_data['change_score'] == 0)
                                                        @php 
                                                            $old_score = number_format($survey_data['old_score']); 
                                                           
                                                        @endphp
                                                            <td class="change_td_data"><div>-{{$old_score}}</div></td>
                                                        @elseif($survey_data['old_score'] == 0)
                                                        @php 
                                                           
                                                            $change_score = number_format($survey_data['change_score']);
                                                        @endphp
                                                        <td class="change_td_data"><div>+{{$change_score}}</div></td>
                                                        @else
                                                        <td class="change_td_data"><div>0</div></td>
                                                        @endif
                                                    @endif                          
                                                    <td class="start_btn3"><div><a href="{{ url('viewsubmission?id='.$survey_data['survey_id'].'&&en='.$tiles_comp['id']) }}">view submission</a></div></td>                            
                                                   
                                                </tr>  
                                            @endif
                                            @endif
                                        @endforeach     
                                    @endforeach 
                                <!-- BOM -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

@elseif(check_role()==2)
<div class="container">
    @php
           $initialState = ''; 
           $completeState= '';
        @endphp
    <div class="row orders-view">
        <h2>
            Dashboard
        </h2>
        @foreach($survey_data_category as $list)
            @php 
                $released_date = date("d/m/Y", strtotime($list->issued_date));
                $target_date = date("d/m/Y", strtotime($list->target_date));
                $stringheading= __('lang.current_surveys');
                $completedheading = __('lang.completed_surveys');
                $countInitial = $getData[$list->id];
                $remediationCount = $countRemediation[$list->id];
            @endphp
            
            @if(strtotime($presentDate) <= strtotime($target_date))
                @if($initialState != $stringheading)
                    @php 
                         $initialState = $stringheading;
                    @endphp
                    <div class="width100 orders-1 mt-3">
                        <h4>
                            {{$stringheading}}
                        </h4>
                    </div>
                @endif
                <div class="initial-active orders-1">
                    <div class="card orders-1 mb-4 mr-2 mt-3" style="width: 18rem;">
                        <div class="card-body">
                            @if($countInitial > 0 )
                            <a class="text-body" href="{{ route('report_active',['id'=>$list->id, $countInitial]) }}">
                                <h5 class="card-title">
                                    {{$list->question_cat_name}}
                                </h5>
                            </a>
                            @else
                            <a class="text-body">
                                <h5 class="card-title">
                                    {{$list->question_cat_name}}
                                </h5>
                            </a>
                            @endif
                            <p class="card-text mb-0">
                                {{__('lang.type')}}:
                                <span class="card-subtitle font-weight-bold">
                                    {{$list->survey_type}}
                                </span>
                            </p>
                            <p class="card-text mb-0">
                                {{__('lang.released')}}: {{$released_date}}
                            </p>
                            <p class="card-text mb-3">
                                {{__('lang.deadline')}}: {{$target_date}}
                            </p>
                            <p class="card-text mb-0">
                                Invitees:
                                <span>
                                    15
                                </span>
                            </p>
                            <p class="card-text mb-0">
                                Complete:
                                <span>
                                    {{$countInitial}}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @elseif(strtotime($presentDate) > strtotime($target_date) )
                @if($initialState != $stringheading)
                        @php 
                             $initialState = $stringheading;
                        @endphp
                    <div class="width100 orders-1 mt-3">
                        <h4>
                            {{$stringheading}}
                        </h4>
                    </div>
                @endif
                <p class="orders-1">
                    No Results Found
                </p>
                @if($completeState != $completedheading)
                    @php 
                        $completeState = $completedheading;
                    @endphp
                    <div class="width100 orders2">
                        <h4>
                            {{$completedheading}}
                        </h4>
                    </div>
                @endif
                <div class="completed-active orders2">
                    <div class="card mb-4 mr-2 mt-3">
                        <div class="card-body">
                            @if($countInitial > 0 )
                                <a class="text-body" href="{{ route('report_initial',['id'=>$list->id, $countInitial]) }}">
                                    <h5 class="card-title">
                                        {{$list->question_cat_name}}
                                    </h5>
                                </a>
                            @else
                                <a class="text-body">
                                    <h5 class="card-title">
                                        {{$list->question_cat_name}}
                                    </h5>
                                </a>
                            @endif
                            <p class="card-text mb-0">
                                {{__('lang.type')}}:
                                <span class="card-subtitle font-weight-bold">
                                    {{$list->survey_type}}
                                </span>
                            </p>
                            <p class="card-text mb-0">
                                {{__('lang.released')}}: {{$released_date}}
                            </p>
                            <p class="card-text mb-3">
                                {{__('lang.deadline')}}: {{$target_date}}
                            </p>
                            <p class="card-text mb-0">
                                {{__('lang.invitees')}}:
                                <span>
                                    15
                                </span>
                            </p>
                            <p class="card-text mb-0">
                                {{__('lang.complete')}}:
                                <span>
                                    {{$countInitial}}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @else
                    @if($completeState != $completedheading)
                        @php 
                            $completeState = $completedheading;
                        @endphp
                        <div class="width100 orders2">
                            <h4>
                                {{$completedheading}}
                            </h4>
                        </div>
                    @endif
                    <p class="completed-active orders2">
                        {{__('lang.no_results_found')}}
                    </p>
            @endif

        @endforeach
    </div>
</div>
@endif

@endsection



