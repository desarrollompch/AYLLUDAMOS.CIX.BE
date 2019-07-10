@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-sm-12">
        <div class="card">
        <div class="card-header">
          <div class="col-md-12">
            <div class="d-flex">
              <div class="col-md-8">
                <h3>Usuario que más uso en el simulacro</h3>
              </div>
              <div class="col-md-4">
                <a href="{{ route('reportes.exportarUsuarioMasUsoSimulacro') }}" class="pull-right btn disenio-boton-accion">Exportar</a>
              </div>
            </div>
            <div class="col-md-12">
              <form action="{{ route('reportes.usuarioMasUsoSimulacro') }}">
                  <div class="form-group row">
                    <div class="col-md-3">
                      <input type="date" class="form-control  datepicker" name="fecha_inicio" value="{{ old('fecha_inicio', $fecha_inicio) }}" id="fecha_inicio" placeholder="Fecha de Inicio"/>
                    </div>
                    <div class="col-md-3">
                      <input type="date" class="form-control datepicker" name="fecha_final" value="{{ old('fecha_final', $fecha_final) }}" id="fecha_final" placeholder="Fecha de Fin"/>
                    </div>
                    <div class="col-md-3">
                      <input type="submit" class="btn btn-primary">
                    </div>
                  </div>
              </form>
          </div>
          </div>
          <div class="limpiar-flotantes"></div> 
        </div>  
        <div class="card-body">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Usuario</th>
                    <th>fecha</th>
                    <th>Usos</th>
                    <th>Sin confirmar</th>
                    <th>Confirmado</th>
                    <th>Falso positivo</th>
                    <th>Atendido</th>
                    <th>En proceso</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ " {$item->ape_paterno} {$item->ape_materno} {$item->nombres}" }}</td>
                    <td>{{ $item->fecha }}</td>
                    <td>{{ $item->contador }}</td>
                    <td>{{ $item->sin_confirmar }}</td>
                    <td>{{ $item->confirmado }}</td>
                    <td>{{ $item->falso_positivo }}</td>
                    <td>{{ $item->atendido }}</td>
                    <td>{{ $item->En_proceso }}</td>
                    <td>{{ $item->puntaje }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $result->appends(request()->query())->links()  }}
        </div>
      </div>
      </div>
      </div>
    </div>
@endsection
@section('scripts')
  <script>
    $('#mute').removeClass('on');
  </script>
@endsection