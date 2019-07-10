<?php

use Faker\Generator as Faker;

$factory->define(App\EstadoIncidente::class, function (Faker $faker) {
    return [
        "descripcion"       => $faker->name
    ];
});
