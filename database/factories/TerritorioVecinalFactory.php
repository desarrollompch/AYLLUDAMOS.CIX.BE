<?php

use Faker\Generator as Faker;

$factory->define(App\TerritorioVecinal::class, function (Faker $faker) {
    return [
        "descripcion"       => $faker->name,
        "coordenadas"       => $faker->text,
        "latitude"          => str_random(30),
        "longitude"         => str_random(30),
        "min_latitude"      => str_random(30),
        "max_latitude"      => str_random(30),
        "max_longitude"     => str_random(30),
        "min_longitude"     => str_random(30),
    ];
});
