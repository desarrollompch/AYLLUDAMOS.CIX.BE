<?php

use Faker\Generator as Faker;

$factory->define(App\TipoPersona::class, function (Faker $faker) {
    return [
        "descripcion"   => $faker->name
    ];
});
