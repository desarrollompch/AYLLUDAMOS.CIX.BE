<?php

use Faker\Generator as Faker;
use App\TerritorioVecinal;

$factory->define(App\Urbanizacion::class, function (Faker $faker) {
    
    $territorioVecinal = TerritorioVecinal::first();
    
    return [
        "territorio_vecinal_id"     => $territorioVecinal->id,
        "coordenadas"				=> $faker->text,
        "latitude"          		=> $faker->latitude(),
        "longitude"         		=> $faker->longitude(),
        "min_latitude"      		=> $faker->latitude(),
        "max_latitude"      		=> $faker->latitude(),
        "max_longitude"     		=> $faker->longitude(),
        "min_longitude"     		=> $faker->longitude(),
        "descripcion"               => $faker->name
    ];
});
