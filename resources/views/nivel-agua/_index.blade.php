@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Nivel Agua

                        <a href="{{route('nivel-agua.create')}}" class="btn disenio-boton-accion float-right">
                          <i class="fa fa-plus fa-lg" aria-hidden="true"></i>Registrar
                        </a>
                    
                    </div>

                    <div class="card-body">

                        <nivel-agua-list></nivel-agua-list>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection