@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Survey List</div>
                    <div class="card-body">
                        {{-- <a href="{{ url('/admin/users/create') }}" class="btn btn-success btn-sm" title="Add New User">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a> --}}

                        

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
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
                               <td>{{$survey->deadline}} <a href="#" onclick="getSurveyId({{$survey->id}})" data-toggle="modal" data-target="#exampleModal">Edit</a></td>
                               <td><a href="#"></a></td>
                               </tr>
                               @endforeach

                                </tbody>
                            </table>
                           
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
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
            <input type="date" name="deadline" id="deadline" required/>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
      </form>     
          </div>
        </div>
      </div>
      @section('scripts')
      <script>

            function getSurveyId(id){
               
              $('#survey_id').val(id);
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
            })
   
       </script>
      @endsection
@endsection