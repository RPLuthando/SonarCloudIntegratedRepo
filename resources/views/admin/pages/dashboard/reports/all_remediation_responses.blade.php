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
                        <h5 class="card-title">{{$list->survey_category_name}}</h5>
                        <p class="card-text mb-0">Type: <span class="card-subtitle font-weight-bold">{{$list->survey_type}}</span></p>
                        @php 
                            $released_date = date("d/m/Y", strtotime($list->issued_date));
                            $target_date = date("d/m/Y", strtotime($list->target_date));
                        @endphp
                        <p class="card-text mb-0">Released:{{$released_date}}</p>
                        <p class="card-text mb-3">Deadline: {{$target_date}}</p>
                        <p class="card-text mb-0">Recipients: <span>{{$value}}</span></p>
                    </div>
                </div>
            @endforeach
            @php
            $values  = [];

            @endphp
          <h4>Recipients not meeting required standard:</h4>
           <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Q</th>
                        <th scope="col">Current</th>
                        <th scope="col">Future</th>
                        <th scope="col">Purchase</th>
                        <th scope="col">Install</th>
                        <th scope="col">Running(pcm)</th>
                        <th scope="col">Start date</th>
                        <th scope="col">End date</th>
                        <th scope="col">Score(current)</th>
                        <th scope="col">Score(future)</th>
                    </tr>
                    @foreach($surveyData as $lists)
                        @php 
                            $userName = $lists->name; 
                             $released_date = date("d/m/Y", strtotime($lists->startdate));
                            $target_date = date("d/m/Y", strtotime($lists->enddate));
                            $specificList = fetch_option_detail($lists->option_id);

                        @endphp
                        <tr>
                            <td> 
                                @if(!in_array($userName, $values))
                                    @php 
                                        $values[] = $userName;
                                    @endphp
                                    {{$userName}}   
                                @endif
                            </td>
                            <td> {{$lists->question_id}} </td>
                            <td> {{$specificList->question_options}}</td>
                            <td> {{$lists->question_options}} </td>
                            <td> {{$lists->purchase}}{{Config::get('currency.symbol.euro')}}</td>
                            <td> {{$lists->install}}{{Config::get('currency.symbol.euro')}}</td>
                            <td> {{$lists->running}}{{Config::get('currency.symbol.euro')}}</td>
                            <td> {{$released_date}}</td>
                            <td> {{$target_date}}</td>
                            <td>{{$specificList->score_current}}</td>  
                            <td>{{$lists->score_current}}</td>
                                                    
                        </tr>  
                    @endforeach
                  
                </table>
            </div>

            <h4>Recipients achieving required standard:</h4>
            <p>(click to view survey response details)</p>
            @foreach($userList as $names)
            <a href="{{ route('report_initial_individual',['id'=>$id, 'user_id'=>$names->id]) }}">
                    <span>{{$names->name}}</span><br/>
            </a>
             @endforeach
        </div>
    </div>
</div>
@endsection

