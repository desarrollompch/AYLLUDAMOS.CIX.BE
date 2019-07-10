<?php

use Faker\Generator as Faker;
use App\TipoPersona;
use App\NivelCiudadano;
use App\EstadoPersona;
use App\Urbanizacion;
// use App\TerritorioVecinal;
use App\Rol;

$factory->define(App\Persona::class, function (Faker $faker) {
    
    // Object tipo_persona
    $tipoPersona = TipoPersona::first();

    // Object nivel_ciudadano
    $nivelCiudadano = NivelCiudadano::first();

    // Object estado_persona
    $estadoPersona = EstadoPersona::first();

    // Object urbanizacion
    $urbanizacion = Urbanizacion::first();

    // Object territorio_vecinal

    // Object rol
    $rol = Rol::first();
    
    return [
        "ape_paterno"           => $faker->lastName,
        "ape_materno"           => $faker->lastName,
        "nombres"               => $faker->name,
        "dni"                   => generarString(),
        "telefono"              => generarString(9),
        "mail"                  => $faker->unique()->safeEmail,
        "direccion"             => $faker->address,
        "tipo_persona_id"       => $tipoPersona->id,
        "nivel_ciudadano_id"    => $nivelCiudadano->id,
        "estado_persona_id"     => $estadoPersona->id,
        "urbanizacion_id"       => $urbanizacion->id,
        "rol_id"                => $rol->id
    ];
});

function generarString($length = 8){
    $caracteres = "0123456789";
    $caracteresLength = strlen($caracteres);
    $randomString = "";
    for($i = 0; $i < $length; $i++) {
        $randomString .= $caracteres[rand(0, $caracteresLength - 1)];
    }
    return $randomString;

}
