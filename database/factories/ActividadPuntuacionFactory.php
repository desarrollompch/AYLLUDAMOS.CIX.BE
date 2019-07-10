<?php

use Faker\Generator as Faker;
use App\EstadoIncidente;

$factory->define(App\ActividadPuntuacion::class, function (Faker $faker) {


	// Object estado_incidente
    $estadoIncidente = EstadoIncidente::first();

    return [
        "descripcion"			=> $faker->text(),
        "puntaje"				=> $faker->randomDigit(),
        "estado_incidente_id"	=> $estadoIncidente->id
    ];
});
