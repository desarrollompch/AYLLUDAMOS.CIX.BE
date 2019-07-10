@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Niveles Ciudadanos

                        <a href="{{route('nivel-ciudadano.create')}}" class="btn btn-primary float-right">
                            Registrar
                        </a>
                    
                    </div>

                    <div class="card-body">

                        <nivel-ciudadano-list></nivel-ciudadano-list>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection