<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<meta name="viewport" content="width=device-width"/>-->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    
    <meta name="csrf-token" id="superTokenBackend" content="{{ csrf_token() }}">    

    <title>{{ config('app.name', 'Rapid Assurance') }}</title>
    <link rel="shortcut icon" href="{{asset('img/current.png') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.1/knockout-debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.min.js" type="text/javascript" charset="utf-8"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/worker-json.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/mode-json.js" type="text/javascript" charset="utf-8"></script>
    

    <!-- Needs to be hosted locally -->

    <script src="/local-sjs/survey.jquery.min-J1.js"></script>
    <script src="/local-sjs/survey-ko-J1.js"></script>
    <link rel="stylesheet" type="text/css"  href="/local-sjs/survey-J1.css"/>
    <link href="/local-sjs/modern-J1.css" type="text/css" rel="stylesheet"/>
    <script src="/local-sjs/survey-creator-J1.js"></script>

    <!-- END Needs to be hosted locally -->
    
    <!--<script src="https://unpkg.com/jquery"></script>-->
    <!--<script src="https://surveyjs.azureedge.net/1.8.9/survey.jquery.min.js"></script>-->
    <!--<link rel="stylesheet" href="https://unpkg.com/survey-creator/survey-creator.css"/>-->
    <!--<link rel="stylesheet" href="./index.css">-->


    {!!Html::style('assets/backend/newdesgin/css/style_1.css')!!}
    {!!Html::style('assets/backend/newdesgin/css/responsive.css')!!}
    {!!Html::style('assets/backend/newdesgin/css/loader_show.css')!!}
    <style>
        .svd_commercial_container{
            display:none;
        }
    </style>

</head>
<body>
<div id="app">
        @include('errors.all_errors')
        
        <header>
            <div class="container-fluid">
                <div class="header">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="/">
                            <div class="logo-section">
                                <div class="das-logo1">
                                    <img src="{{ asset('img/images/logo.png') }}" class="img-fluid">
                                </div>
                                <div class="pow-by">
                                    <p>ASSURANCE PROVIDED BY</p>
                                </div>
                                <div class="das-logo2">
                                <?php                           
                            if (str_contains(Request::fullUrl(), 'payu.rapidassurance.ai')) {
                        ?>        
                              <img src="{{ asset('img/images/logoV1.1.png') }}" class="img-fluid">       
                        <?php
                            } elseif (str_contains(Request::fullUrl(), 'uat.rapidassurance.ai')) {
                        ?>        
                                <img src="{{ asset('img/images/logoUAT.png') }}" class="img-fluid">  
                        <?php                       
                            } elseif (str_contains(Request::fullUrl(), 'prod.rapidassurance.ai')) {
                        ?>      
                                <img src="{{ asset('img/images/logoV1.1.png') }}" class="img-fluid">        
                        <?php                          
                            } elseif (str_contains(Request::fullUrl(), '127.0.0.1:8000')) {
                        ?>
                                <img src="{{ asset('img/images/logoUAT.png') }}" class="img-fluid"> 
                        <?php        
                            }
                        ?>
                                    <!--<img src="{{ asset('img/images/logo2.png') }}" class="img-fluid">-->
                                </div>
                            </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <div class="right-top">
                                <div class="user-section"> 
                                <div class="dropdown">
                                    <div class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="{{ asset('img/images/user.png') }}"> {{ Auth::user()->name }}
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a>
                                        
                                    </div>
                                </div>
                                </div>
                                <!-- Logout Code start here-->
                                <div class="dropdown-menu pull-right" aria-labelledby="navbarDropdown">
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                                <!-- Logout End -->
                                <!--<div class="pagename">
                                <p> @if(check_role()==2) (Management User) @elseif(check_role()==3) (Responsible User) {{check_role()}} @elseif(check_role()==1) (SuperUser) @endif</p>
                                </div>-->
                                <div class="help-section">
                                <!--<a href=""><img src="{{ asset('img/images/help.png') }}"></a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

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

    
    <!--{!!Html::script('assets/backend/newdesgin/js/popper.min.js')!!} 
    {!!Html::script('assets/backend/newdesgin/js/bootstrap.min.js')!!}-->
    {!!Html::script('assets/backend/newdesgin/js/loader_show.js')!!}
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




