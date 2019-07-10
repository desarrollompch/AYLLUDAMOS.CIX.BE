@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Log de Usuarios
                    </div>
                    <div class="card-body">

                        <log-list></log-list>

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