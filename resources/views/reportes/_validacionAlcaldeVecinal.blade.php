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
                <h3>Validaciones por alcalde vecinal</h3>
              </div>
              <div class="col-md-4">
                <a href="{{ route('reportes.exportarValidacionAlcaldeVecinal') }}" class="pull-right btn disenio-boton-accion">Exportar</a>
              </div>
            </div>
            <div class="col-md-12">
              <form action="{{ route('reportes.validacionAlcaldeVecinal') }}">
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
        </div>  
        <div class="card-body">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Persona</th>
                    <th>Urbanización</th>
                    <th>Territorio vecinal</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result as $item)
                <tr>
                    <td>{{ $item->persona_id_validador }}</td>
                    <td>{{ "{$item->ape_paterno} {$item->ape_materno} {$item->nombres}" }}</td>
                    <td>{{ $item->urbanizacion }}</td>
                    <td>{{ $item->territorioVecinal }}</td>
                    <td>{{ $item->contador }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
          <div class="col-md-12 nopadding">
            <div class="d-flex"> 
              <div class="col-md-7 nopadding">
                {{ $result->appends(request()->query())->links()  }}
              </div>
              <div class="col-md-5 pull-right nopaddding">
                <form action="{{ route('reportes.validacionAlcaldeVecinal') }}" method="GET">
                  <input type="hidden" name="fecha_inicio" value="<?php echo $fecha_inicio?>" />
                  <input type="hidden" name="fecha_final" value="<?php echo $fecha_final?>" />
                  <div class="d-flex">
                    <div class="col-md-5">
                      <label for="numero_filas">Número de filas:</label>
                    </div>
                    <div class="col-md-3">
                      <select name="numero_filas" id="numero_filas" class="form-control">
                        <option value="10" {{old('numero_filas',$numero_filas)=="10"? 'selected':''}}>10</option>
                        <option value="15" {{old('numero_filas',$numero_filas)=="15"? 'selected':''}}>15</option>
                        <option value="20" {{old('numero_filas',$numero_filas)=="20"? 'selected':''}}>20</option>
                        <option value="25" {{old('numero_filas',$numero_filas)=="25"? 'selected':''}}>25</option>
                        <option value="30" {{old('numero_filas',$numero_filas)=="30"? 'selected':''}}>30</option>
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