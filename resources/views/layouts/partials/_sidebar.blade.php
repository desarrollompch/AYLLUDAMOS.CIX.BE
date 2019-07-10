@inject('permisos', 'App\Services\Permisos')
<nav class="side-navbar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar"><img src="{{asset('img/avatar-1.jpg')}}" alt="..." class="img-fluid rounded-circle"></div>
        <div class="title">
            <h1 class="h4">{{ Auth::user()->name }}</h1>
        </div>
    </div>
    <!-- Sidebar Navidation Menus-->
    <span class="heading">Principal</span>
    <ul class="list-unstyled">
        <li>
          <a href="{{url('/')}}"> <i class="icon-home"></i>Inicio</a>
        </li>
        @foreach($permisos->getMenu() as $item)
            @if(isset($item['routes']) && count($item['routes']))
              <li>
                <a href="#{{$item['id']}}" aria-expanded="false" data-toggle="collapse"> 
                  <i class="icon-interface-windows"></i> {{$item['module']}} 
                </a>
                <ul id="{{$item['id']}}" class="collapse list-unstyled ">
                    @foreach($item['routes'] as $route)
                        <li><a href="{{$route['url']}}">{{$route['name']}}</a></li>
                    @endforeach
                </ul>
              </li>
            @else
              <li><a href="{{$item['url']}}">  {{$item['module']}} </a></li>
            @endif
        @endforeach
        <li><a href="#consultas-link" aria-expanded="false" data-toggle="collapse"> <i
                        class="icon-interface-windows"></i> Consultas </a>
            <ul id="consultas-link" class="collapse list-unstyled ">
                {{-- <li><a href="{{ route('reportes.ranking-ciudadanos-puntuacion') }}">Ranking ciudadanos por puntuación</a></li> --}}
                {{-- <li><a href="{{ route('reportes.incidentes-atendidos') }}">Incidentes atendidos</a></li> --}}
                {{-- <li><a href="{{ route('reportes.incidentes-por-atender') }}">Incidentes por atender</a></li> --}}
                {{-- <li><a href="{{ route('reportes.incidentes-por-tipo-incidente') }}">Incidentes por tipo de incidente</a></li> --}}
                <li><a href="{{ route('reportes.ranking-ciudadanos-puntuacion') }}">Ciudadanos por puntuación</a></li>
                <li><a href="{{ route('reportes.incidentes-atendidos') }}">Incidentes atendidos</a></li>
                <li><a href="{{ route('reportes.incidentes-por-atender') }}">Incidentes por atender</a></li>
                <li><a href="{{ route('reportes.incidentes-por-tipo-incidente') }}">Incidentes por tipo de incidente</a></li>
                <li><a href="{{ route('reportes.territorioMasDescarga') }}">Territorios con mas usuarios registrados</a></li>
                <li><a href="{{ route('reportes.usuarioMasUsoSimulacro') }}">Usuario que más uso en el simulacro</a></li>
                <li><a href="{{ route('reportes.usuarioMasUsoAppFecha') }}">Persona que ha usado mas la aplicación a la fecha</a></li>
                <li><a href="{{ route('reportes.personaRegistradaFecha') }}">Personas registradas hasta la fecha</a></li>
                <li><a href="{{ route('reportes.validacionAlcaldeVecinal') }}">Validaciones por alcalde vecinal</a></li>
            </ul>
        </li>
    </ul>

</nav>