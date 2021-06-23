<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="superTokenBackend" content="{{ csrf_token() }}">    

    <title>{{ config('app.name', 'Rapid Assurance') }}</title>
	<link rel="shortcut icon" href="{{asset('img/current.png') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.1/knockout-debug.js"></script>
    <link rel="stylesheet" type="text/css"  href="https://unpkg.com/survey-knockout/survey.css"/>
   
   <!-- Added unpkg/ survey-knockout 28 OCT -->
    <script src="https://unpkg.com/survey-knockout"></script> 
    <!-- End-->
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
    
    <style>
        .svd_commercial_container{
            display:none;
        }
        /* Tooltip CSS */
        .wrapper {
            text-transform: uppercase;
            font-weight: bolder;
            color: #6d7072;
            cursor: help;
            position: relative;
            -webkit-transform: translateZ(0); /* webkit flicker fix */
            -webkit-font-smoothing: antialiased; /* webkit text rendering fix */
        }

        .wrapper .tooltip {
            overflow: scroll;
            height: 1px;
            font-weight: bold !important;
            background: #5a57bf;
            bottom: 100%;
            color: #fff;
            display: block;
            margin-bottom: -15px;
            opacity: 0;
            padding: 20px;
            pointer-events: none;
            position: relative;
            width: auto;
            -webkit-transform: translateY(10px);
            -moz-transform: translateY(10px);
            -ms-transform: translateY(10px);
            -o-transform: translateY(10px);
            transform: translateY(10px);
            -webkit-transition: all 0.25s ease-out;
            -moz-transition: all 0.25s ease-out;
            -ms-transition: all 0.25s ease-out;
            -o-transition: all 0.25s ease-out;
            transition: all 0.25s ease-out;
            -webkit-box-shadow: 2px 2px 6px rgb(0 0 0 / 28%);
            -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
            -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
            -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
            z-index: 99999999 !important;
            box-shadow: 2px 2px 6px rgb(0 0 0 / 28%);
        }

        /* This bridges the gap so you can mouse into the tooltip without it disappearing */
        .wrapper .tooltip:before {
            bottom: -20px;
            content: " ";
            display: block;
            height: 20px;
            left: 0;
            position: absolute;
            width: 100%;
        }

        /* CSS Triangles - see Trevor's post */
        .wrapper .tooltip:after {
            border-left: solid transparent 10px;
            border-right: solid transparent 10px;
            border-top: solid #5a57bf 10px;
            bottom: -10px;
            content: " ";
            height: 0;
            left: 50%;
            margin-left: -13px;
            position: absolute;
            width: 0;
        }

        /*.wrapper:hover .tooltip {
            opacity: 1;
            overflow: scroll;
            height: 150px;
            pointer-events: auto;
            -webkit-transform: translateY(0px);
            -moz-transform: translateY(0px);
            -ms-transform: translateY(0px);
            -o-transform: translateY(0px);
            transform: translateY(0px);
        }*/
        .wrapper:hover .tooltip {
            height: auto;
            /* position: unset; */
            opacity: 1;
            overflow: hidden;
            /* height: 150px; */
            pointer-events: auto;
            -webkit-transform: translateY(0px);
            -moz-transform: translateY(0px);
            -ms-transform: translateY(0px);
            -o-transform: translateY(0px);
            transform: translateY(0px);
        }

        /* IE can just show/hide with no transition */
        .lte8 .wrapper .tooltip {
            display: none;
        }

        .lte8 .wrapper:hover .tooltip {
            display: block;
        }
    </style>

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
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link loginflag" href="{{ url('/login') }}">Login</a></li>
                            
                        @else
                            <li class="nav-item dropdown">
                                
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Logged in as   {{ Auth::user()->email }} ROLE-> @if(check_role()==2) (Management User) @elseif(check_role()==3) (Responsible User) {{check_role()}} @elseif(check_role()==1) (SuperUser) @endif <span class="caret"></span>
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

            @yield('contents')

        </main>

        <hr/>

        <div class="container">
            
            <br/>
        </div>

    </div>

 <!-- Scripts -->
    <script type="text/javascript">
        $(function () {
            // Navigation active
            $('ul.navbar-nav a[href="{{ "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}"]').closest('li').addClass('active');
        });
    </script>

@yield('scripts') 
<script src="{{asset('/assets/backend/js/custom.js')}}"></script>
<!--<script>
    $(document).ready(function() {
        $('body').bind('cut copy paste', function(e) {
            e.preventDefault();
        });
    });
    $(document).ready(function() {
        $("body").on("contextmenu", function(e) {
            return false;
        });
    });
</script>-->
{!!Html::script('assets/backend/js/surveyjs/survey_details.js')!!}
{!!Html::script('assets/backend/js/surveyjs/survey_creator.js')!!}
</body>
</html>




