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
                  <h3>Frecuencia de uso</h3>
                </div>
                <div class="col-md-4">
                  <a href="{{ route('reportes.exportar-frecuencia-uso') }}" class="pull-right btn disenio-boton-accion">Exportar</a>
                </div>
              </div>
            </div>
          </div>
            <div class="col-md-12 mt-15">
                <form action="{{ route('reportes.frecuencia-uso') }}">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <input type="date" class="form-control  datepicker" name="fecha_inicio" id="fecha_inicio" placeholder="Fecha de Inicio" value="{{ old('fecha_inicio', $fecha_inicio) }}"/>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control datepicker" name="fecha_final" id="fecha_final" placeholder="Fecha de Fin" value="{{ old('fecha_final', $fecha_final) }}"/>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
          <div class="card-body">
            <table class="table table-hover table-striped table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Nombres</th>
                        <th>Fecha</th>
                        <th>Incidencias</th>
                        <th>Sin Confirmar</th>
                        <th>Confirmar</th>
                        <th>Falso positivo</th>
                        <th>Atendido</th>
                        <th>En Proceso</th>
                        <th>Puntaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->ape_paterno }}</td>
                        <td>{{ $item->ape_materno }}</td>
                        <td>{{ $item->nombres }}</td>
                        <td>{{ $item->fecha }}</td>
                        <td>{{ $item->incidencias }}</td>
                        <td>{{ $item->sin_confirmar }}</td>
                        <td>{{ $item->confirmado }}</td>
                        <td>{{ $item->falso_positivo }}</td>
                        <td>{{ $item->atendidos }}</td>
                        <td>{{ $item->en_proceso }}</td>
                        <td>{{ $item->puntaje }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="col-md-12 nopadding">
              <div class="d-flex">
                <div class="col-md-7 nopadding">
                  {{ $result->appends(request()->query())->links()  }}
                </div>
                <div class="col-md-5 pull-right nopadding">
                  <form action="{{ route('reportes.frecuencia-uso') }}" method="GET">
                    <div class="d-flex">
                      <div class="col-md-5">
                        <label for="numero_filas">Número de filas:</label>
                      </div>
                      <div class="col-md-3">
                        <input type="hidden" name="fecha_inicio" value="<?php echo $fecha_inicio?>" />
                        <input type="hidden" name="fecha_final" value="<?php echo $fecha_final?>" />
                        <select name="numero_filas" id="numero_filas" class="form-control">
                          <option value="10" {{old('numero_filas',$numero_filas)=="10"? 'selected':''}}>10</option>
                          <option value="20" {{old('numero_filas',$numero_filas)=="20"? 'selected':''}}>20</option>
                          <option value="30" {{old('numero_filas',$numero_filas)=="30"? 'selected':''}}>30</option>
                          <option value="40" {{old('numero_filas',$numero_filas)=="40"? 'selected':''}}>40</option>
                          <option value="50" {{old('numero_filas',$numero_filas)=="50"? 'selected':''}}>50</option>
                          <option value="60" {{old('numero_filas',$numero_filas)=="60"? 'selected':''}}>60</option>
                        </select>
                      </div>
                      <div class="col-md-4 nopadding">
                        <input type="submit" class="btn disenio-boton-accion btn-block" value="Filtrar" />
                      </div>
                    </div>
                  </form>
                </div>
              </div>
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