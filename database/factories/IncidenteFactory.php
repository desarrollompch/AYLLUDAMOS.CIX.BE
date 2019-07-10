<?php

use Faker\Generator as Faker;
use App\Persona;
use App\EstadoIncidente;
use App\TipoIncidente;
use App\urbanizacion;

$factory->define(App\Incidente::class, function (Faker $faker) {

    // Object persona
    $persona = Persona::find(2);

    // Object estado_incidente
    $estadoIncidente = EstadoIncidente::first();

    // Object tipo_incidente
    $tipoIncidente = TipoIncidente::first();

    //Objeto Urbanizacion
    $urbanizacion = Urbanizacion::first();


    return [
        "fecha"                 => $faker->date(),
        "descripcion"           => $faker->text(),
        "direccion"             => $faker->address,
        "longitud"              => $faker->longitude(),
        "latitud"               => $faker->latitude(),
        "imagen"                => "", 
        "urbanizacion_id"       => $urbanizacion->id, 
        "persona_id"            => $persona->id, 
        "estado_incidente_id"   => $estadoIncidente->id,
        "tipo_incidente_id"     => $tipoIncidente->id,        
    ];
});
