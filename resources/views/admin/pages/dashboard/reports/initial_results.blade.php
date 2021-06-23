@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <h2>SURVEY RESULTS</h2>
            </div>
            @foreach($survey_data_list as $list)
                <div class="card order-1 mb-4 mr-2 mt-3" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{$list->question_cat_name}}</h5>
                        <p class="card-text mb-0">Type: <span class="card-subtitle font-weight-bold">{{$list->survey_type}}</span></p>
                        @php 
                            $released_date = date("d/m/Y", strtotime($list->issued_date));
                            $target_date = date("d/m/Y", strtotime($list->target_date));
                            //$value = $getData[$list->id];
                        @endphp
                        <p class="card-text mb-0">Released:{{$released_date}}</p>
                        <p class="card-text mb-3">Deadline: {{$target_date}}</p>
                        <p class="card-text mb-0">Recipients : <span>{{$value}}</span></p>
                      
                    </div>
                </div>
            @endforeach
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Q</th>
                        <th scope="col">Question</th>
                        <th scope="col">Response</th>
                        <th scope="col">Score</th>
                        <th scope="col">Standard?</th>
                    </tr>
                            @php
                                $values  = [];
                                $a =1 ;
                            @endphp 
                    @foreach($surveyData as $lists)
                            @php 
                                $userName = $lists->name; 
                                 $count = countValues($lists->user_id);

                            @endphp
                        <tr >
                            <td> 
                                @if(!in_array($userName, $values))
                                    @php 
                                        $values[] = $userName;
                                    @endphp
                                    {{$userName}}   
                                @endif
                            </td>

                            <td> {{$lists->question_id}} </td>

                            <td> {{$lists->question_name}} </td>

                            <td> {{$lists->question_options}} </td>

                            <td> {{$lists->score_current}} 
                            </td>

                            <td>
                                @if($lists->ideal_standard == 'No')
                                    <p>No</p>
                                @else
                                    <p>Yes</p>
                                @endif
                            </td>
                        </tr>  
                
                        @if($a % $count[0]['questions_count'] == 0)
                            <tr class="list" id="list{{$lists->question_id}}" count_id="{{$lists->question_id}}">
                                <td></td>
                                <td></td>
                                <td></td> 
                                <td class="text-right">Total Score:</td>  
                                <td> {{$count[0]['questions_scores']}}/{{$count[0]['questions_count']}}</td>
                                <td></td> 
                            </tr>  
                        @endif
                    @php  $a++; @endphp        
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
       // $('.list').not(':last').hide();
      // $('.list').each(function() {
      //      // alert( this.id );
      //       console.log(this.id );
      //    $(this.count_id) 
      //   });
    });
</script>