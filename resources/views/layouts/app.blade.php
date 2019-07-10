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
    <!-- <link href="{{ asset('css/fontastic.css') }}" rel="stylesheet"> -->

    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('css/all.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/custom/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>
</head>
<body>
    @if(Request::segment(1) == 'login')
    <div id="app"></div>
    @endif

    <!-- Código del header -->
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
        @include('layouts.partials.header')
        @guest
            <div class="page-content d-flex align-items-stretch">
                <div class="content-inner">
                    <div class="projects mb-15">
                        @yield('content')
                    </div>
                </div>
            </div>
        @else
        <div class="page-content d-flex align-items-stretch">
            @include('layouts.partials.sidebar')
            <div class="col-sm-10">
            <!-- <div class="col-sm-9 offset-3 main mt-15">      -->
                <div class="mt-15">
                    @yield('content')
                </div>
                <!-- <div class="row">
                    <div class="col-sm-12">
                        <p class="back-link">Desarrollado por <a href="http://adylconsulting.com/">AD y L Consulting</a></p>
                    </div>
                </div> -->
            </div>
            <div class="limpiar-flotantes"></div>            
        </div>
        @endguest
    </div>
    <script src="//maps.google.com/maps/api/js?libraries=geometry&key={{env('MAPS_API_KEY')}}" type="text/javascript"></script>
    <script src="{{ asset('js/gmaps.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
            integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
            crossorigin=""></script>
    <script src="https://npmcdn.com/leaflet.path.drag/src/Path.Drag.js"></script>
    <script src="{{ asset('js/l-editable.js') }}"></script>

    @routes
    <!-- Scripts -->
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="{{ asset(mix('js/all.js')) }}"></script>
    @yield('scripts')
</body>
</html>
