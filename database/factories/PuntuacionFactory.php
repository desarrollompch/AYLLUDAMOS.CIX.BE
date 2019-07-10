<?php

use Faker\Generator as Faker;

$factory->define(App\Puntuacion::class, function (Faker $faker) {
    return [
        "total_minimo"  => $faker->randomDigit(),
        "total_maximo"  => $faker->randomDigit()
    ];
});
