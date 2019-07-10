@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Usuario

                        {{-- <a href="{{route('user.create')}}" class="btn disenio-boton-accion float-right">
                            <i class="fa fa-plus fa-lg" aria-hidden="true"></i>Registrar
                        </a> --}}
                    
                    </div>

                    <div class="card-body">
                      {{-- <div id="mute">
                        <div id="content-mute">
                          <img src="{{ asset('img/loader.gif') }}" alt="{{ config('app.name') }}" />
                        </div>
                      </div> --}}
                      <user-list></user-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection