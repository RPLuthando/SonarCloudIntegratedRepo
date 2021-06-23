@extends('layouts.backend')

@section('content')
<div class="container">
     
    <div class="row justify-content-center">
             
        <div class="col-md-9">
            <div class="">
                <div class="card-body pt-0">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
    <h2>Edit information </h2>
    <form method="POST" action=" {{route('entitylist.update',['id' => $id])}} ">
        {{csrf_field()}}
        {{ method_field('PATCH')}}       
        <input type="hidden" value="{{$id}}" name="edit_entity_id">
        {{-- <input type="hidden" value="{{$user_type}}" name="user_type"> --}}
        {{-- <input type="hidden" id="new_user_id" name="new_user_id" value="{{$email}}"> --}}
        <div class="form-group">
            <label for="new_entity_name">Name:</label>
            <input type="text" class="form-control" id="edit_entity_name" name="edit_entity_name" value="{{ $entityDetails->name }}" required>
        </div>
        <!--<div class="form-group">
            <label class="col-md-4 control-label" for="rolename">Group</label>
                <div class="col-md-4">                        
                    <select name="groupName" id="groupName">
                        <option value="">Select Group</option>
                            <option value="security" {{ $data[0][group] == 'security'  ? 'selected' : ''}} >Security</option>
                            <option value="privacy" {{ $data[0][group] == 'privacy'  ? 'selected' : ''}} >Privacy</option>
                            <option value="securityandprivacy" {{ $data[0][group] == 'securityandprivacy'  ? 'selected' : ''}} >Security & Privacy</option>
                            <option value="privacyuat" {{ $data[0][group] == 'privacyuat'  ? 'selected' : ''}} >Privacy UAT</option>
                            <option value="securityuat" {{ $data[0][group] == 'securityuat'  ? 'selected' : ''}} >Security UAT</option>
                            <option value="admin" {{ $data[0][group] == 'admin'  ? 'selected' : ''}} >Admin</option>
                        </select>
                </div>
        </div>-->
        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Update</button>
        </div>
        
    </form>
    
    <?php  
       
    ?>

    <form method="POST" action=" {{route('resetsurvey.update')}} ">
        {{csrf_field()}}
        {{ method_field('PATCH')}}
        <div class="form-group">
            <label class="col-md-4 control-label" for="rolename">Reset Survey</label>
                <div class="col-md-12">  
                <input type="hidden" value="{{$id}}" name="entity_id">
                <?php
                //dd($entity_data->group);
                    //$heading_count = 0;
                    for ($i=0; $i < count($current_revision_array); $i++) {    
                        $heading = strtoupper($current_revision_array[$i]['group']) ;

                        if($heading !== $display_heading_test) {
                            echo '<b>' . $heading . '</b>';
                        }

                            if ($current_revision_array[$i]['display'] === false) {
                                //echo '<b>' . $heading . '</b>';
                                echo '<label class="container">';
                                echo '<input type="checkbox" name="reset[]" value="' . $current_revision_array[$i]['survey_id'] .'" readonly="readonly" style="display:none"> ';
                                echo ' <span class="checkmark"></span> ' . $current_revision_array[$i]['name'] . ': (Current Review Stage: <strong>' . $current_revision_array[$i]['revision_number'] . '</strong>)</label> ';
                            } elseif($current_revision_array[$i]['selected'] === true) {
                                //echo '<b>' . $heading . '</b>';
                                echo '<label class="container">';
                                echo '<input type="checkbox" name="reset[]" value="' . $current_revision_array[$i]['survey_id'] .'" style="display:none"> ';
                                echo ' <span class="checkmark"></span> ' . $current_revision_array[$i]['name'] . ': (Current Review Stage: <strong>' . $current_revision_array[$i]['revision_number'] . '</strong>)</label> ';
                            }  else {
                                //echo '<b>' . $heading . '</b>';
                                echo '<label class="container">';
                                echo '<input type="checkbox" name="reset[]" value="' . $current_revision_array[$i]['survey_id'] .'"> ';
                                echo ' <span class="checkmark"></span> ' . $current_revision_array[$i]['name'] . ': (Current Review Stage: <strong>' . $current_revision_array[$i]['revision_number'] . '</strong>)</label> ';
                            }  
                            $display_heading_test = $heading;    
                    }
                ?>            
                    <button style="cursor:pointer" type="submit" class="btn btn-primary">Reset</button> 
                    <p></p>
                    <p><b>STAGES:</b> <br/>Initial > 0 (First Review) > Reset > 1,2,3...</p>      
                </div>
        </div>
    </form>    
                </div>
                @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
            </div>
        </div>
    </div>
</div>
@endsection
