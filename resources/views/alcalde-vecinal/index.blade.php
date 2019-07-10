@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Alcalde Vecinal

                        <a href="{{route('alcalde-vecinal.create')}}" class="btn disenio-boton-accion float-right">
                            <i class="fa fa-plus fa-lg" aria-hidden="true"></i>Registrar
                        </a>
                    
                    </div>

                    <div class="card-body">

                        <alcalde-vecinal-list></alcalde-vecinal-list>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection