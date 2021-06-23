@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <h2>SURVEY DATA</h2>
            </div>
           
            <div class="table-responsive">
                <table class="table table-bordered">
                    
                    
                    @foreach($survey_data_category as $lists)
                    
                    <tr class="mb-3">
                        <td><b>Q:{{ $loop->iteration }}:{{$lists->question_name}}</b></td>
                    </tr>
                    <tr>
                        <th scope="col">Option</th>
                        <th scope="col">Score</th>
                        <th scope="col">Standard</th>
                    </tr>
                        @foreach($lists->questionTitles as $titles)
                            <tr>
                                <td>{{$titles->question_options}}</td>
                                <td>{{$titles->score_current}}</td>
                                <td>{{$titles->ideal_standard}}</td>
                            </tr>
                        @endforeach
                    @endforeach                    
                </table>
            </div>    
        </div>
    </div>
</div>
@endsection

