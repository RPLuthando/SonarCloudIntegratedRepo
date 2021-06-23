<html lang="en"> 

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" id="superTokenBackend" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Rapid Assurance') }}</title> 
    {!!Html::style('assets/backend/newdesgin/css/bootstrap.min.css')!!}
    <link rel="shortcut icon" href="{{asset('img/current.png') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/local-sjs/jquery.dataTables.min-J1.css">
    {!!Html::style('assets/backend/newdesgin/css/style_1.css')!!}
    {!!Html::style('assets/backend/newdesgin/css/responsive.css')!!}
    {!!Html::style('assets/backend/newdesgin/css/loader_show.css')!!}
    
</head>

<body>
    
    <div class="main-wraper"> 
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
        @if (Session::has('flash_message'))
                        <div class="container">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ Session::get('flash_message') }}
                            </div>
                        </div>
                        @endif
            @yield('content')
    </div>
    {!!Html::script('assets/backend/newdesgin/js/jquery.min.js')!!}
    {!!Html::script('assets/backend/newdesgin/js/popper.min.js')!!}
    {!!Html::script('assets/backend/newdesgin/js/bootstrap.min.js')!!}
    {!!Html::script('assets/backend/newdesgin/js/loader_show.js')!!}
    
    <script type="text/javascript" src="/local-sjs/jquery.dataTables.min-J1.js"></script>
    <script src="{{asset('/assets/backend/newdesgin/js/custom.js')}}"></script>
<!--    <script>
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
     
</body> 
</html>