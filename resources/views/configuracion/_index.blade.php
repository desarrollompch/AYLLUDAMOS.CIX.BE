@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                       Configuración

                        <a href="{{route('configuracion.create')}}" class="btn btn-primary float-right">
                            Registrar
                        </a>
                    
                    </div>

                    <div class="card-body">

                        <configuracion-list></configuracion-list>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection