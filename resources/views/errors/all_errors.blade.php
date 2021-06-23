@if($errors->count()>0)
    <div class="card">
        <div class="card-body">            
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                <span style="color:red">{{ $error }} </span><br/>
            </div>
            @endforeach
        </div>  
    </div>
@endif

{{-- this file is being used in backend.blade.php Layout file..... Comments are not meant to be remove on this project. --}}