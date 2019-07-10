<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,700" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/fontastic.css') }}" rel="stylesheet">

    <link href="{{ asset(mix('css/all.css')) }}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">

    <link href="{{ asset('css/mycss.css') }}" rel="stylesheet">

    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous"> --}}

</head>
<body>
@if(Request::segment(1) == 'login')
<div id="app"></div>
@endif
{{-- CODIGO DEL LOADER --}}
<div id="mute" class="on h100">
  <div id="content-mute">
    <div style="width: 350px;margin-top: 13%;margin-left: 45%;">
        <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
        <circle fill="#fff" stroke="none" cx="6" cy="50" r="6">
          <animateTransform 
             attributeName="transform" 
             dur="1s" 
             type="translate" 
             values="0 15 ; 0 -15; 0 15" 
             repeatCount="indefinite" 
             begin="0.1"/>
        </circle>
        <circle fill="#fff" stroke="none" cx="30" cy="50" r="6">
          <animateTransform 
             attributeName="transform" 
             dur="1s" 
             type="translate" 
             values="0 10 ; 0 -10; 0 10" 
             repeatCount="indefinite" 
             begin="0.2"/>
        </circle>
        <circle fill="#fff" stroke="none" cx="54" cy="50" r="6">
          <animateTransform 
             attributeName="transform" 
             dur="1s" 
             type="translate" 
             values="0 5 ; 0 -5; 0 5" 
             repeatCount="indefinite" 
             begin="0.3"/>
        </circle>
      </svg>
    </div>
  </div>
</div>
<div class="page" @if(Request::segment(1) != 'login')id="app"@endif>
    <!-- Main Navbar-->
    @include('layouts.partials.header')
    @guest
        <div class="page-content d-flex align-items-stretch">
            <div class="content-inner">
                <div class="projects" style="margin-top: 15px">
                    @yield('content')
                </div>
            </div>
        </div>
    @else
        <div class="page-content d-flex align-items-stretch">
            <!-- Side Navbar -->
            @include('layouts.partials.sidebar')
            <div class="content-inner">
                <!-- Page Header-->
                <div class="projects" style="margin-top: 15px">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                <!-- Page Footer-->
                <footer class="main-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Your company &copy; 2017-2019</p>
                            </div>
                            <div class="col-sm-6 text-right">
                                <p>Design by <a href="https://bootstrapious.com/admin-templates" class="external">Bootstrapious</a>
                                </p>
                                <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    @endguest
</div>

<script src="//maps.google.com/maps/api/js?libraries=geometry&key={{env('MAPS_API_KEY')}}"
        type="text/javascript"></script>
<script src="{{ asset('js/gmaps.min.js') }}"></script>
@routes
<!-- Scripts -->
<script src="{{ asset(mix('js/app.js')) }}"></script>
<script src="{{ asset(mix('js/all.js')) }}"></script>
@yield('scripts')
</body>
</html>
