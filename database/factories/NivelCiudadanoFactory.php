<?php

use Faker\Generator as Faker;
use App\Puntuacion;

$factory->define(App\NivelCiudadano::class, function (Faker $faker) {
    
    // Object puntuacion
    $puntuacion = Puntuacion::first();
    
    return [
        "icono"         => $faker->sentence(1),
        "descripcion"   => $faker->sentence(2),
        "puntuacion_id" => $puntuacion->id
    ];
});
