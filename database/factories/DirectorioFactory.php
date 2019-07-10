<?php

use Faker\Generator as Faker;

$factory->define(App\Directorio::class, function (Faker $faker) {
    return [
        "nombre"		=> $faker->company,	
        "direccion"		=> $faker->address,
        "telefono"		=> $faker->e164PhoneNumber
    ];
});
