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
                <h3>Personas registradas</h3>
              </div>
              <div class="col-md-4">
                <a href="{{ route('reportes.exportarPersonaRegistradaFecha') }}" class="pull-right btn disenio-boton-accion">Exportar</a>
              </div>
            </div>
          </div>
          <div class="limpiar-flotantes"></div> 
        </div>  
        <div class="card-body">
        <table class="table table-hover table-striped table-custom">
            <thead>
                <tr>
                    <th width="5%">Codigo</th>
                    <th width="10%">Dni</th>
                    <th width="10%">Nombres</th>
                    <th width="10%">Apellidos</th>
                    <th width="10%">Fecha registro</th>
                    <th width="10%">Rol</th>
                    <th width="10%">Urbanización</th>
                    <th width="15%">Territorio vecinal</th>
                    <th width="10%">Usuario</th>
                    <th width="10%">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result as $item)
                <tr>
                    <td width="5%">{{ $item->persona_id }}</td>
                    <td width="10%">{{ $item->dni }}</td>
                    <td width="10%">{{ $item->nombres }}</td>
                    <td width="10%">{{ $item->ape_paterno }} {{ $item->ape_materno }}</td>
                    <td width="10%">{{ $item->fechaRegistro }}</td>
                    <td width="10%">{{ $item->roles }}</td>
                    <td width="10%">{{ $item->urbanizacion }}</td>
                    <td width="15%">{{ $item->territorioVecinal }}</td>
                    <td width="10%">{{ $item->mail }}</td>
                    <td width="10%">{{ $item->estado }}</td>
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
                <form action="{{ route('reportes.personaRegistradaFecha') }}" method="GET">
                  <div class="d-flex">
                    <div class="col-md-5">
                      <label for="numero_filas">Número de filas:</label>
                    </div>
                    <div class="col-md-3">
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