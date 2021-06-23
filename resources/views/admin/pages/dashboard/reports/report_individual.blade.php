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
                            $target_date = date("d/m/Y", strtotime($list->target_date));
                        @endphp
                        <p class="card-text mb-0">Completed: {{$target_date}}</p>
                        <p class="card-text mb-0">First view: dd/mm/yy</p>
                        <p class="card-text mb-3">Number of views: 4</p>
                        <p class="card-text mb-0"><b>Karl Turkovic</b></p>
                        <p class="card-text mb-0">email@email.com</p>
                        <p class="card-text mb-0">01234 567 890</p>
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
                        $userName= fetch_user_info($user_id);
                        $stringPrevent = '';
                        $name = $userName->name;
                    @endphp
                    
                    @foreach($questionFixture as $view)
                       <tr>
                            <td>   
                                @if( $stringPrevent != $name )
                                    @php
                                        $stringPrevent = $name;
                                    @endphp
                                    {{$name}}
                                @endif
                                <br/>
                            </td>
                            <td>
                                {{$view->id}} 
                            </td>
                            <td>
                                {{$view->question_name}}                           
                            </td>
                            <td>{{$view->question_options}}</td>
                            <td>{{$view->score_current}}</td>
                            <td>{{$view->ideal_standard}}</td>
                        </tr>
                    @endforeach  
                     
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">Total Score:</td>
                        <td class="text-center">({{$sum}}/{{$total}})</td>
                        <td></td>
                    </tr>
                </table>
            </div>    
        </div>
    </div>
    <a href="{{ URL::previous() }}" class="btn btn-primary"> <i class="fa fa-arrow-left"></i> Go Back</a>
</div>

@endsection

