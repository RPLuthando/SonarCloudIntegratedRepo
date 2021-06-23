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
                    <h2>Add new {{fancy_the_role($user_type)}}</h2>
                   
                    <form method="POST" action="/addinfo">
                    {{ csrf_field() }}                      
                        <input type="hidden" value="{{$user_type}}" name="user_type"> 
                        {{-- <input type="hidden" id="new_user_id" name="new_user_id" value="{{$email}}"> --}}
                        
                        <div class="form-group">
                            <label for="new_user_name">Name:</label>
                            <input type="text" class="form-control" id="new_user_name" name="new_user_name" value="{{ old('new_user_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="new_user_email">Email:</label>
                            <input type="text" class="form-control" id="new_user_email" name="new_user_email" value="{{ old('new_user_email') }}">
                        </div>
                        
                       @if($user_type=='responsible_user')
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="entityname">Entity Name</label>
                            <div class="col-md-4">
                                <select id="framework" class="form-control" name="entity_name[]" multiple="multiple" style="display: none">
                                    @foreach($entity_type as $enti)
                                    <option value="{{$enti->id}}">{{$enti->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                @endif     
                       <!-- BOM -->
                       <div class="form-group">
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
                        </div> 
                       <!-- BOM -->
                        <div class="form-group">
                            <button style="cursor:pointer" type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
