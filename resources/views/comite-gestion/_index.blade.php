@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Comites de Gestión

                        <a href="{{route('comite-gestion.create')}}" class="btn disenio-boton-accion float-right">
                            <i class="fa fa-plus fa-lg" aria-hidden="true"></i>Registrar
                        </a>
                    
                    </div>

                    <div class="card-body">

                        <comite-gestion-list></comite-gestion-list>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection