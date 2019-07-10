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
    <link href="{{ asset(mix('css/all.css')) }}" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @if(Auth::check())
                        <li class="nav-item"><a class="nav-link" href="{{ route('tipo-persona.index') }}">Tipo Personas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('territorio-vecinal.index') }}">Territorios Vecinales</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('urbanizacion.index') }}">Urbanizaciones</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('rol.index') }}">Roles</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('nivel-ciudadano.index') }}">Nivel Ciudadano</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('persona.index') }}">Personas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.index') }}">Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('estado-incidente.index') }}">Estados Incidentes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('tipo-incidente.index') }}">Tipos Incidentes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('nivel-agua.index') }}">Niveles Agua</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('tipo-obstaculo.index') }}">Tipo de Obstaculo</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('incidente.index') }}">Incidente</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('alcalde-vecinal.index') }}">Alcaldes Vecinales</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('comite-gestion.index') }}">Comite Gestión</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('directorio.index') }}">Directorio</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('actividad-puntuacion.index') }}">Actividad Puntuación</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('coordinacion.index') }}">Coordinación</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret">Consultas</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <ul class="navbar-nav">
                                    <li class="nav-item"><a class="nav-link" href="{{ route('reportes.ranking-ciudadanos-puntuacion') }}">Ranking ciudadanos por puntuación</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('reportes.incidentes-atendidos') }}">Incidentes atendidos</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('reportes.incidentes-por-atender') }}">Incidentes por atender</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('reportes.incidentes-por-tipo-incidente') }}">Incidentes por tipo de incidente</a></li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
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
        @yield('content')
    </main>
</div>
<script src="//maps.google.com/maps/api/js?libraries=geometry&key=AIzaSyDBGVDv5fOFgfW4ixNZL_2krgkriGu6vvc"
        type="text/javascript"></script>
<script src="{{ asset('js/gmaps.min.js') }}"></script>
@routes
<!-- Scripts -->
<script src="{{ asset(mix('js/app.js')) }}" defer></script>
<script src="{{ asset(mix('js/all.js')) }}" defer></script>
</body>
</html>
