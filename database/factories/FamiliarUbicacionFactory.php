<?php

use Faker\Generator as Faker;
use App\Familiar;

$factory->define(App\FamiliarUbicacion::class, function (Faker $faker) {
    
    // Object Familia
    $familiar = Familiar::find(1);

    return [     
        "familiar_id"	=> $familiar->id,
        "fecha"			=> $faker->dateTime,
        "latitude"		=> $faker->latitude(),
        "longitude"		=> $faker->longitude(),
        "descripcion"	=> $faker->text()
    ];
});
