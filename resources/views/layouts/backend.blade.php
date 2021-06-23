<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="superTokenBackend" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rapid Assurance') }}</title>

    <!-- Styles -->
	<link rel="shortcut icon" href="{{asset('img/current.png') }}">
            <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" 
	rel="Stylesheet"type="text/css"/>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.1/knockout-debug.js"></script>
    <link rel="stylesheet" type="text/css"  href="https://unpkg.com/survey-knockout/survey.css"/>
    
    {{-- Css for Entity drop down --}}
    <link src="{{asset('/assets/backend/css/entity.css')}}" />

    <script src="https://unpkg.com/survey-knockout"></script> 

    
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.min.js"
      type="text/javascript"
      charset="utf-8" 
    ></script>
    
    <script
          src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/worker-json.js"
          type="text/javascript"
          charset="utf-8"
        ></script>
    
    <script
          src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/mode-json.js"
          type="text/javascript"
          charset="utf-8"
        ></script>

    <script src="https://unpkg.com/survey-creator"></script>


    <link
      rel="stylesheet"
      href="https://unpkg.com/survey-creator/survey-creator.css"
    />

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    @yield('css_include')
</head>
<body>
    <div id="app">
        @include('errors.all_errors')
        
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Rapid Asssurance') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- <ul class="navbar-nav mr-auto">
                        <li><a href="{{ url('/admin') }}">Dashboard <span class="sr-only">(current)</span></a></li>
                    </ul> --}}

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link loginflag" href="{{ url('/login') }}">Login</a></li>
                            {{-- <li><a class="nav-link" href="{{ url('/register') }}">Register</a></li> --}}
                        @else
                            <li class="nav-item dropdown">
                                
                                <!--<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Logged in as   {{ Auth::user()->email }} ROLE-> @if(check_role()==2) (Management User) @elseif(check_role()==3) (Responsible User) {{check_role()}} @elseif(check_role()==1) (SuperUser) @endif <span class="caret"></span>
                                </a>-->
								
								<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Logged in as   {{ Auth::user()->email }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu pull-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
             
            @if (Session::has('flash_message'))
                <div class="container">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ Session::get('flash_message') }}
                    </div>
                </div>
            @endif

            @yield('content')

        </main>

        <hr/>

        <div class="container">
            
            <br/>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.1/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '.crud-richtext'
        });
    
        $(function () {
            // Navigation active
            $('ul.navbar-nav a[href="{{ "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"]').closest('li').addClass('active');
        });
        $(document).ready(function(){
            
            $(".alert").fadeTo(2000, 500).slideUp(500, function(){
                    $(".alert").slideUp(500);
            });

        });
    </script>
    

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@yield('scripts') 
<script src="{{asset('/assets/backend/js/custom.js')}}"></script> 
{{-- Js for entity dropdown in responsable user --}}
<!-- jQuery library -->
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

<!-- Popper JS -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> --}}

<!-- Latest compiled JavaScript -->
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  --}}
<script src="{{asset('/assets/backend/js/entity-drop.js')}}"></script> 
<script src="{{asset('/assets/backend/js/entity-search-drop.js')}}"></script>
 
{!!Html::script('assets/backend/js/surveyjs/survey_details.js')!!}
{!!Html::script('assets/backend/js/surveyjs/survey_creator.js')!!}

</body>
</html>
