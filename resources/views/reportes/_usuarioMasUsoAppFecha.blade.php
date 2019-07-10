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
                <h3>Persona que ha usado más la aplicación hasta la fecha</h3>
              </div>
              <div class="col-md-4">
                <a href="{{ route('reportes.exportarUsuarioMasUsoAppFecha') }}" class="pull-right btn disenio-boton-accion">Exportar</a>
              </div>
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
                    <th>Incidente</th>
                    <th>Estado</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ "{$item->nombres} {$item->ape_paterno} {$item->ape_materno}" }}</td>
                    <td>{{ $item->urbanizacion }}</td>
                    <td>{{ $item->territorioVecinal }}</td>
                    <td>{{ $item->incidente }}</td>
                    <td>{{ $item->confirmado }}</td>
                    <td>{{ $item->puntaje }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- {{ $result->appends(request()->query())->links()  }} -->
          <div class="col-md-12 nopadding">
            <div class="d-flex"> 
              <div class="col-md-7 nopadding">
                {{ $result->appends(request()->query())->links()  }}
              </div>
              <div class="col-md-5 pull-right nopaddding">
                <form action="{{ route('reportes.usuarioMasUsoAppFecha') }}" method="GET">
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