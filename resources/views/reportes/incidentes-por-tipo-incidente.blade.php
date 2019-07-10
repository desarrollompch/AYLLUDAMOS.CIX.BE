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
                    <h3 style="margin-bottom:20px">Incidentes por tipo de incidente</h3>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('reportes.incidentes-por-tipo-incidente-excel') }}" class="pull-right btn disenio-boton-accion">Exportar</a>
                </div>
            </div>
          </div>
        <div class="col-md-12">
            <form action="{{ route('reportes.incidentes-por-tipo-incidente') }}">
                <div class="form-group row">
                  <div class="col-md-3">
                    <select name="tipo_incidente_id" id="tipo_incidente_id" class="form-control">
                        <option value="0">-- Seleccionar --</option>
                        @foreach($estadoIncidente as $estado)
                        <option value="{{ $estado->id }}">{{ $estado->descripcion }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <input type="date" class="form-control  datepicker" name="fecha_inicio" id="fecha_inicio" placeholder="Fecha de Inicio"/>
                  </div>
                  <div class="col-md-3">
                    <input type="date" class="form-control datepicker" name="fecha_final" id="fecha_final" placeholder="Fecha de Fin"/>
                  </div>
                  <div class="col-md-3">
                    <input type="submit" class="btn btn-primary">
                  </div>
                </div>
            </form>
        </div>
        </div>
        <div class="card-body">
        <div>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Dirección</th>
                        <th>Urbanización</th>
                        <th>Territorio Vecinal</th>
                        <th>Estado Incidente</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($result) === 0)
                        <tr>
                            <td colspan="5">No existen registros</td>
                        </tr>
                    @else
                        @foreach($result as $incidente)
                        <tr>
                            <td>{{ $incidente->fecha }}</td>
                            <td>{{ $incidente->direccion }}</td>
                            <td>{{ $incidente->urbanizacion }}</td>
                            <td>{{ $incidente->territorio_vecinal }}</td>
                            <td>{{ $incidente->estado_incidente }}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            {{ $result->appends(request()->query())->links()  }}
        </div>
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