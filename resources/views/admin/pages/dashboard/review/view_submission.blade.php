@extends('layouts.responsible_backend')

@section('content')
{!!Html::style('assets/backend/css/dash_style.css')!!}  
  <section class="pb-5">
    <div class="container-fluid mt-4">
      <div class="panel-box">
        <div class="pay-credit-title">
          <h3>{{$entityName->name}}, {{$surveyDetailJson->name}}</h3>
        </div>
        <div class="review_compt">
          <p>Review complete</p> 
        </div>
      </div>
      <div class="table-content mt-4">
        <div id="accordion" class="accordion">
          <div class="card mb-0 tablecard">
            <div class="card-header collapsed" data-toggle="collapse" href="#collapsethree">
              <a class="card-title">
                Your current status:
              </a>
            </div>
            <div id="collapsethree" class="card-body collapse show" data-parent="#accordion" >
              <div>
                <table id="Your_submission" class="display cell-border datatable_content" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Section</th>
                      <th>Question</th>
                      <th>Comment</th>
                      <th>Score</th>
                      <th>Change</th>
                      <th class="text-center">Potential</th>
                      <th class="text-center">Compliance</th>
                     
                    </tr>
                  </thead>    
                
                  <tbody>
                  @if(isset($dataCreatedJson))
                    @foreach($dataCreatedJson as $key=>$dataget)  
                   
                      <tr>
                        
                        @if((!empty($dataget['change']) || ($dataget['change'] == '0') && $dataget['type'] == 'radiogroup'))
                            
                            @if(!empty($dataget['section']))
                              <td class="reviewed_Data"><div><p>{{$dataget['section']}}</p></div></td>
                              @else
                              <td class="reviewed_Data"><div></div></td>
                            @endif

                            
                            @if(!empty($dataget['question']))
                                  <td> 
                                    {{$dataget['question']}} 
                                    @if(!empty($dataget['answered']) || $dataget['answered'] == 0 || $dataget['answered'] == null)
                                      <br>
                                      >
                                      {{$dataget['answered']}}
                                    @endif
                                  </td>
                                @endif

                            @if(!empty($dataget['reviewCommentvalue'])) 
                                  <td class="reviewed_Data" ><div><b class="comment">{{$dataget['reviewCommentvalue']}}</b></div></td>
                                 
                                  @else
                                  <td class="reviewed_Data"><div></div></td>   
                            @endif
                            <!-- BOM??? -->
                            @if(($dataget['score']) > ($dataget['change']))
                                
                                <td class="reviewed_Data score_data" style="text-align: center"><div><b>{{number_format($dataget['change'],2)}}</b></div></td>
                                @php
                                $completed_change_1 = $dataget['score'] - $dataget['change']
                                @endphp
                                <td class ="score_data" style="text-align: center"><b>-{{number_format($completed_change_1,2)}}</b></td>
                                @php
                                $completed_potential_1 = $dataget['max'] - $dataget['change']
                                @endphp
                                <td class ="score_data" style="text-align: center"><b>{{number_format($completed_potential_1,2)}}</b></td>

                              @elseif(($dataget['score']) < ($dataget['change']))
                               
                                <td class="reviewed_Data score_data" style="text-align: center"><div><b>{{number_format($dataget['change'],2)}}</b></div></td>
                                @php
                                $completed_change_2 = $dataget['change'] - $dataget['score']
                                @endphp
                                <td class ="score_data" style="text-align: center"><b>+{{number_format($completed_change_2,2)}}</b></td>
                                @php
                                $completed_potential_2 = $dataget['max'] - $dataget['change']
                                @endphp
                                <td class ="score_data" style="text-align: center"><b>{{number_format($completed_potential_2,2)}}</b></td>

                              @elseif(($dataget['score']) == ($dataget['change']))

                                <td class="reviewed_Data score_data" style="text-align: center"><div><b>{{number_format($dataget['change'],2)}}</b></div></td>
                                @php
                                $completed_change_3 = $dataget['change'] - $dataget['score']
                                @endphp
                                <td class ="score_data" style="text-align: center"><b>{{number_format($completed_change_3,2)}}</b></td>
                                @php
                                $completed_potential_3 = $dataget['max'] - $dataget['change']
                                @endphp
                                <td class ="score_data" style="text-align: center"><b>{{number_format($completed_potential_3,2)}}</b></td>
                            @endif

                                {{--@if(!empty($dataget['stand']))
                                  <td class="cmpliance_status" style="color: #ef5454; text-align: center"><b>{{$dataget['stand']}}</b></td>
                                @else
                                  <td class="cmpliance_status" style="color: #ef5454; text-align: center">Non-Standard</td>
                                @endif--}}
 
                                @if(!empty($dataget['stand'] === 'Ideal') || !empty($dataget['stand'] === 'Optimized'))
                                    <td class="cmpliance_status" style="color: #2da377">{{$dataget['stand']}}</td>

                                @elseif(!empty($dataget['stand'] === 'Acceptable') || !empty($dataget['stand'] === 'Managed') || !empty($dataget['stand'] === 'Defined'))
                                    <td class="cmpliance_status" style="color: #000000">{{$dataget['stand']}}</td>

                                @elseif(!empty($dataget['stand'] === 'Non-Standard') || !empty($dataget['stand'] === 'Emerging') || !empty($dataget['stand'] === 'Initial'))
                                    <td class="cmpliance_status" style="color: #ef5454">{{$dataget['stand']}}</td>
                                @else
                                    <td class ="score_data" style="text-align: center">-</td>
                                @endif

                            @elseif($dataget['type'] == 'text')

                              @if(!empty($dataget['section']))
                                <td class="reviewed_Data"><div><p>{{$dataget['section']}}</p></div></td>
                                @else
                                <td class="reviewed_Data"><div></div></td>
                              @endif

                              
                              @if(!empty($dataget['question']))
                                  <td> 
                                    {{$dataget['question']}} 
                                    @if(!empty($dataget['answered']) || $dataget['answered'] == 0 || $dataget['answered'] == null)
                                      <br>
                                      >
                                      {{$dataget['answered']}}
                                    @endif
                                  </td>
                                @endif
                              @if(!empty($dataget['reviewCommentvalue'])) 
                                  <td class="reviewed_Data"><div><b class="comment">{{$dataget['reviewCommentvalue']}}</b></div></td>
                                 
                                @else
                                  <td class="reviewed_Data" style="color: #ef5454"><b></b></td>
                              @endif

                              <td class ="score_data" style="text-align: center">-</td>


                              <td class ="score_data" style="text-align: center">-</td>
                              <td class ="score_data" style="text-align: center">-</td>
                              <td class ="score_data" style="text-align: center">-</td>

                            @else
                                @if(isset($dataget['section']))
                                    <td class="reviewed_Data"><div><p>{{$dataget['section']}}</p></div></td>
                                  @else
                                    <td class="reviewed_Data"><div></div></td>
                                @endif
                               
                                @if(!empty($dataget['question']))
                                  <td> 
                                    {{$dataget['question']}} 
                                    @if(!empty($dataget['answered']) || $dataget['answered'] == 0 || $dataget['answered'] == null)
                                      <br>
                                      >
                                      {{$dataget['answered']}}
                                    @endif
                                  </td>
                                @endif 
                              
                                @if(!empty($dataget['comment']))
                                    <td><div>{{$dataget['comment']}}</div></td>
                                    @else
                                    <td><div></div></td> 
                                @endif 
                                
                                @if(!empty($dataget['score']) && $dataget['type'] != 'text')

                                  <td class="reviewed_Data score_data" style="text-align: center"><div>{{number_format($dataget['score'],2)}}</div></td>
                                  @elseif($dataget['type'] != 'text')
                                  <td class="reviewed_Data score_data" style="text-align: center"><div>0.00</div></td>
                                @else
                                  <td></td>   
                                @endif

                                <td></td>   
                                @php
                                $completed_potential = $dataget['max'] - $dataget['score']
                                @endphp   
                                <td class ="score_data" style="text-align: center">{{number_format($completed_potential,2)}}</td>



                                {{--@if(!empty($dataget['standard']) && $dataget['type'] != 'text')
                                  <td class="cmpliance_status" style="color: #ef5454">{{$dataget['standard']}}</td>
                                @elseif($dataget['type'] != 'text' )
                                  <td class="cmpliance_status" style="color: #ef5454">Non-Standard</td>
                                @else
                                  <td></td>
                                @endif--}}
                                    @if(!empty($dataget['standard'] === 'Ideal') || !empty($dataget['stand'] === 'Optimized'))
                                        <td class="cmpliance_status" style="color: #2da377">{{$dataget['standard']}}</td>

                                    @elseif(!empty($dataget['standard'] === 'Acceptable') || !empty($dataget['stand'] === 'Managed') || !empty($dataget['stand'] === 'Defined'))
                                        <td class="cmpliance_status" style="color: #000000">{{$dataget['standard']}}</td>

                                    @elseif(!empty($dataget['standard'] === 'Non-Standard') || !empty($dataget['stand'] === 'Emerging') || !empty($dataget['stand'] === 'Initial'))
                                        <td class="cmpliance_status" style="color: #ef5454">{{$dataget['standard']}}</td>
                                    @else
                                        <td class ="score_data" style="text-align: center">-</td>
                                    @endif
                               
                                
                        @endif 
                      </tr>
                    @endforeach
                  @else
                    <tr><td>No Record Found</td></tr>
                  @endif  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-4">
          <div class="saveNo_btn reprot_title"> 
            <a href="/" class="border-gradient border-gradient-purple">
              Return to dashboard
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  
@endsection
   


