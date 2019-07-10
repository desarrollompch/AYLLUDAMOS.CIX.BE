@inject('permisos', 'App\Services\Permisos')
<div id="sidebar-collapse" class="col-sm-2 sidebar">
    <div class="profile-sidebar">
        @if(Auth::user())
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">{{ Auth::user()->name }}</div>
            <div class="profile-usertitle-status"><span class="indicator label-success"></span>En Linea</div>
        </div>
        @endif
        <div class="clear"></div>
    </div>
    <div class="divider"></div>
    <ul class="list-unstyled nav menu">
        <li>
          <a href="{{url('/')}}"> <i class="fa fa-home"></i>&nbsp; Inicio</a>
        </li>
        @foreach($permisos->getMenu() as $item)
            @if(isset($item['routes']) && count($item['routes']))
              <li>
                <a href="#{{$item['id']}}" aria-expanded="false" data-toggle="collapse"> 
                  <i class="fa fa-arrow-down"></i>&nbsp; {{$item['module']}} 
                </a>
                <ul id="{{$item['id']}}" class="collapse list-unstyled nav">
                    @foreach($item['routes'] as $route)
                        <li><a href="{{$route['url']}}"><i class="fa fa-check"></i>&nbsp; {{$route['name']}}</a></li>
                    @endforeach
                </ul>
              </li>
            @else
              <li><a href="{{$item['url']}}"><i class="fa fa-link"></i>&nbsp;{{$item['module']}} </a></li>
            @endif
        @endforeach
        <li><a href="#consultas-link" aria-expanded="false" data-toggle="collapse"> <i
                        class="fa fa-arrow-down"></i>&nbsp; Reportes </a>
            <ul id="consultas-link" class="collapse list-unstyled nav">
                <li><a href="#consultas-sublink-ciudadanos" aria-expanded="false" data-toggle="collapse">
                    <i class="fa fa-arrow-down"></i>&nbsp; Ciudadanos</a>
                    <ul id="consultas-sublink-ciudadanos" class="collapse list-unstyled nav">
                        <li><a href="{{ route('reportes.ranking-ciudadanos-puntuacion') }}"><i class="fa fa-check"></i>&nbsp;Ranking</a></li>
                        <li><a href="{{ route('reportes.personaRegistradaFecha') }}"><i class="fa fa-check"></i>&nbsp;Personas registradas</a></li>
                        <li><a href="{{ route('reportes.frecuencia-uso') }}"><i class="fa fa-check"></i>&nbsp;Frecuencia de uso</a></li>
                        <!-- <li><a href="{{ route('reportes.usuarioMasUsoAppFecha') }}"><i class="fa fa-check"></i>&nbsp;Persona que ha usado mas la aplicación a la fecha</a></li> -->
                    </ul>
                </li>
                <li><a href="#consultas-sublink-incidencias" aria-expanded="false" data-toggle="collapse">
                    <i class="fa fa-arrow-down"></i>&nbsp; Incidencias</a>
                    <ul id="consultas-sublink-incidencias" class="collapse list-unstyled nav">
                        <li><a href="{{ route('reportes.incidentes-registrados-fecha') }}"><i class="fa fa-check"></i>&nbsp;Incidentes a la Fecha</a></li>
                        <li><a href="{{ route('reportes.incidentes-por-ciudadano') }}"><i class="fa fa-check"></i>&nbsp;Incidentes por Ciudadano</a></li>
                    </ul>
                </li>
                <li><a href="#consultas-sublink-territorios" aria-expanded="false" data-toggle="collapse">
                    <i class="fa fa-arrow-down"></i>&nbsp; Territorios Vecinales</a>
                    <ul id="consultas-sublink-territorios" class="collapse list-unstyled nav">
                        <li><a href="{{ route('reportes.ciudadanos-registrados-territorio-vecinal') }}"><i class="fa fa-check"></i>&nbsp;Ciudadanos Registrados</a></li>
                        <li><a href="{{ route('reportes.incidencias-validadas-por-alcalde-vecinal') }}"><i class="fa fa-check"></i>&nbsp;Incidencias Validadas por Alcalde Vecinal</a></li>
                        <li><a href="{{ route('reportes.territorios-vecinales-registrados') }}"><i class="fa fa-check"></i>&nbsp;Territorios Vecinales registrados</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        @if(Auth::user())
        <li>
            <a href="login.html" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="fa fa-window-close"></i>&nbsp; {{ __('Logout') }}</a>
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                style="display: none;">
            @csrf
        </form>
        @endif
    </ul>
</div>