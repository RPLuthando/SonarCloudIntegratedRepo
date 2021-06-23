@extends('layouts.backend')

@section('content')
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row justify-content-center">
            
        <div class="col-md-9">
            <div class="">
                <div class="card-body pt-0">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                   
    <h2>Add new entity</h2>
    <form method="POST" action="/entitylist/store">
        {{ csrf_field() }}
        {{-- <input type="hidden" value="{{$user_type}}" name="user_type"> --}}
        {{-- <input type="hidden" id="new_user_id" name="new_user_id" value="{{$email}}"> --}}
        <div class="form-group">
            <label for="new_entity_name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Create Entity</button>
        </div>
    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
