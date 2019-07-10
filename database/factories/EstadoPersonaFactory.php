<?php

use Faker\Generator as Faker;

$factory->define(App\EstadoPersona::class, function (Faker $faker) {
    return [
        "descripcion"       => $faker->name
    ];
});
