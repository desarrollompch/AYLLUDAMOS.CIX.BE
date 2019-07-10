<?php

use Faker\Generator as Faker;
use App\Incidente;
use App\Persona;

$factory->define(App\AtencionIncidente::class, function (Faker $faker) {
    
    $incidente = Incidente::find(1);
    $persona = Persona::find(4);

    return [
        "persona_id" 	=> $persona->id,
        "incidente_id"	=> $incidente->id,
        "fecha"			=> $faker->dateTime,
        "descripcion"	=> $faker->text
    ];
});
