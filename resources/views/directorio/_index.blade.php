@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                       Directorios

                        <a href="{{route('directorio.create')}}" class="btn btn-primary float-right">
                            Registrar
                        </a>
                    
                    </div>

                    <div class="card-body">

                        <directorio-list></directorio-list>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection