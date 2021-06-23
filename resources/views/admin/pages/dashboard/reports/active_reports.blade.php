@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <h2>ACTIVE SURVEY STATUS</h2>
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
                        <p class="card-text mb-0">Invitees : <span>15</span></p>
                        <p class="card-text mb-0">Complete : <span>{{$value}}</span></p>
                      
                    </div>
                </div>
            @endforeach
            <div class="mb-3">
                <h4>Completed Surveys</h4>
            </div>  
            <div class="table-responsive" style="width: 18rem;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                              <th></th>
                              <th class="text-center">Date completed</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach($getUserData as $views)
                         @php 

                        @endphp
                        <tr>
                            @php 
                                 $completion_date = date("d/m/Y", strtotime($views->userList['updated_at']));
                            @endphp
                            <td>{{$views->userList['name']}}</td>
                            <td class="text-center">{{$completion_date}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
           {{--  <a class="text-body" href="{{ route('report_initial_results',['id'=>$list->id, $value]) }}"> --}}
             <a href="{{ route('report_initial_results',['id'=>$id, $value]) }}" class="btn btn-info">VIEW RESULTS</a>
        </div>
    </div>
</div>
@endsection

