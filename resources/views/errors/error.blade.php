
        @if(Session::has('flash_message'))
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success" role="alert">
                    {{Session::get('flash_message')}}
                </div>
            </div>
        </div>
        @elseif(Session::has('error_message'))
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger" role="alert">
                    {{Session::get('error_message')}}
                </div>  
            </div>
        </div>
    
        @endif