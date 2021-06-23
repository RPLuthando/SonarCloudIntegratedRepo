@extends('layouts.backend')

@section('content')
<div class="container">
       
    <div class="row justify-content-center">
            @include('admin.sidebar')
        <div class="col-md-9">
            <div class="">
                <div class="card-body pt-0">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                           
                        <div class="text-left"><h3>Survey List</h3></div>
                        <div class="text-right"><button data-bind="click: function() { createSurvey('NewSurvey' + Date.now(), loadSurveys); }" class="btn btn-info">Create New Survey</button></div>

                    </div>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.no.</th><th>Name</th><th>Deadline</th><th>Action</th>
                                   
                                </tr>
                            </thead>
                            <tbody> 
                                <?php $count=0; ?>
                                @foreach($getSurvey as $survey)
                                <tr>
                                 <td>{{++$count}}</td>
                                 <td>{{$survey->name}}</td>
                                 <input type="hidden" name="date" value="{{date("m-d-Y", strtotime($survey->deadline))}}"> 
                                <td>{{date("m-d-Y", strtotime($survey->deadline))}} <a  href="#" onclick="getSurveyId({{$survey->id}},{{$survey->deadline}})" data-toggle="modal" data-target="#exampleModal">Edit</a></td>
                                <td class="text-nowrap">
                                    <ul class="list-inline m-0">
                                        
                                            <li class="list-inline-item">
                                                    
                                            <a data-bind="attr: { href: 'editor?id=' + ko.unwrap({{$survey->id}}) }"><i class="fa fa-edit"></i></a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="{{ url('deletelive/'.$survey->id.'') }}" ><i class="fa fa-trash"></i>
                                                </a>
                                        </li>
                                        </ul></td>
                                </tr>
                                @endforeach
                            </tbody>
                           
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Delete Survey Record</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
      <form method="post" id="deleteSurvey">
            <div class="modal-body">
            {{ csrf_field() }}
            <input type="hidden" name="survey1_id" id="survey1_id">
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Delete</button>
            </div>
      </form>     
          </div>
        </div>
</div>
{{-- <input type="text" id="datepicker"> --}}



<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Deadline Date</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
  <form method="post" id="frm">
        <div class="modal-body">
        {{ csrf_field() }}
        <input type="hidden" name="survey_id" id="survey_id">
        <input type="text" name="deadline" id="datepicker" placeholder="mm/dd/yyyy" required/>
        </div>
        <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
  </form>     
      </div>
    </div>
  </div>
 
@endsection
@section('datepicker_css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

      function getSurveyId(id,date1){
        
        $('#survey_id').val(id);
        $('#datepicker').val(data1);
      }

      $(document).ready(function(){
        $('#frm').on('submit', function(event){ 
            event.preventDefault();
                $.ajax({
                url:"{{ route('updateSurveyDeadline') }}",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    if(data.success){
                      window.location.reload('true');
                    }else{
                      //   alert()
                    }
                }
            })
        });
     
        $( "#datepicker" ).datepicker({
            minDate:0
        });
});
 </script>
@endsection
<style>

</style>