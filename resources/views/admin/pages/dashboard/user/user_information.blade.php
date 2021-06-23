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
                            @if(\Request::segment(2)!=="all_users" || \Request::segment(2)=="super_user" || \Request::segment(2)=="responsible_user" || \Request::segment(2)=="management_user" )
                        <div class="text-left"><h3>{{fancy_the_role(\Request::segment(2))}} List</h3></div>
                   
                        <div class="text-right"><a href="{{ url('/adduserinfo/'.\Request::segment(2).'') }}" class="btn btn-info" role="button">Add new  {{fancy_the_role(\Request::segment(2))}} </a></div>
                    @endif
                    </div>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                   {{--  <th class="text-nowrap">Contact Information</th>
                                    <th>Address</th>--}}
                                    <th>Action</th> 
                                </tr>
                            </thead>
                            <tbody> 
                                    <?php //echo "<pre>";print_r($users); die; ?> 
                                    @foreach($users as $u)
                                    @php 
                                    $user = fetch_user_info($u->user_id); 
                                    // dd($user);
                                    @endphp
                                        @if(!empty($user))
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            {{-- <td>{{$user->phone}}</td>
                                            <td>{{$user->address}}</td> --}}
                                            <td class="text-nowrap">
                                                    <ul class="list-inline m-0">
                                                            @if($user->deleted_at==null)
                                                            <li class="list-inline-item">
                                                            <a href="{{ url('edit/user/'.$user->id.'') }}" ><i class="fa fa-edit"></i></a>
                                                            </li>
                                                            
                                                            <li class="list-inline-item">
                                                                    <a href="{{ url('delete/user/'.$user->id.'') }}" ><i class="fa fa-trash"></i>
                                                                    </a>
                                                            </li>
                                                            @else
                                                            Deleted
                                                            <li class="list-inline-item">
                                                                <a href="{{ url('recover/user/'.$user->id.'') }}" >Recover
                                                                </a>
                                                            </li>
                                                            @endif
                                                        </ul></td>
                                            
                                        </tr>
                                        @endif
                                @endforeach
                            </tbody>
                           
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
