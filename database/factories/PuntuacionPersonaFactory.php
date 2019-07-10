<?php

use Faker\Generator as Faker;

$factory->define(App\PuntuacionPersona::class, function (Faker $faker) {
    return [
        "numero_incidente"  		=> $faker->randomDigit(),
        "actividad_puntuacion_id"	=> $faker->randomDigit(),
        "persona_id"				=> $faker->randomDigit()
    ];
});
