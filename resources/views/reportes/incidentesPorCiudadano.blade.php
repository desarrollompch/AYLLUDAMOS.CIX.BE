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
                  <h3>Incidentes Por Ciudadanos</h3>
                </div>
                <div class="col-md-4">
                  <a href="{{ route('reportes.exportar-incidentes-por-ciudadano') }}" class="pull-right btn disenio-boton-accion">Exportar</a>
                </div>
              </div>
            </div>
          </div>  
          <div class="col-md-12">
            <form action="{{ route('reportes.incidentes-por-ciudadano') }}">
              <div class="form-group row">
                <div class="col-md-3 mt-15">
                    <input type="text" name="parametro" id="parametro" class="form-control" placeholder="Ingrese ID o DNI de un ciudadano" />
                </div>
                <div class="col-md-3 mt-15">
                  <input type="submit" class="btn btn-primary" value="Consultar">
                </div>
              </div>
            </form>
          </div>
          <div class="card-body">
            <table class="table table-hover table-striped table-custom">
                <thead>
                    <tr>
                        <th width="15%">Ciudadano</th>
                        <th width="15%">DNI</th>
                        <th width="15%">Fecha - Hora</th>
                        <th width="15%">Dirección</th>
                        <th width="15%">Urbanización</th>
                        <th width="15%">Territorio Vecinal</th>
                        <th width="10%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $item)
                    <tr>
                        <td width="15%">{{ $item->nombres }} {{ $item->ape_paterno }} {{ $item->ape_materno }}</td>
                        <td width="15%">{{ $item->dni }}</td>
                        <td width="15%">{{ $item->created_at }}</td>
                        <td width="15%">{{ $item->direccion }}</td>
                        <td width="15%">{{ $item->urbanizacion }}</td>
                        <td width="15%">{{ $item->territorio_vecinal }}</td>
                        <td width="10%">{{ $item->estado_incidente }}</td>                        
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
                  <form action="{{ route('reportes.incidentes-por-ciudadano') }}" method="GET">
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