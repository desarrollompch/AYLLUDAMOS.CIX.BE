<?php

use Faker\Generator as Faker;

$factory->define(App\TipoIncidente::class, function (Faker $faker) {
    return [
        "descripcion"       => $faker->name
    ];
});
