<!DOCTYPE html>
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
{!!Html::style('assets/backend/newdesgin/css/style_1.css')!!}
{!!Html::style('assets/backend/newdesgin/css/responsive.css')!!}
</head>

<body>
    <div class="login-screen">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login-logo">
                        <div class="login-logo1">
                            <img src="{{ asset('img/images/logo.png') }}" class="img-fluid">
                       <!-- <img src="images/logo.png" class="img-fluid"> -->
                        </div>
                        <div  class="login-logo2">
                            <p>ASSURANCE PROVIDED BY</p>
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
                            
                       <!-- <img src="images/logo2.png" class="img-fluid"> -->
                        </div>
                    </div>
                    <div class="login-section">
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
               
               </div>
            </div>
        </div>
    </div>
       {!!Html::script('assets/backend/newdesgin/js/jquery.min.js')!!}
       {!!Html::script('assets/backend/newdesgin/js/popper.min.js')!!}
       {!!Html::script('assets/backend/newdesgin/js/bootstrap.min.js')!!}
       <script src="{{ asset('js/app.js') }}"></script>
       <script src="{{asset('/assets/backend/js/custom.js')}}"></script> 
       <!-- <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script> -->
</body>
</html>