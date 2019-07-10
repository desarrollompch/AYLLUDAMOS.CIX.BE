@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <notificacion-list></notificacion-list>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>
                <div class="card-body">
                    <dashboard-front></dashboard-front>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
