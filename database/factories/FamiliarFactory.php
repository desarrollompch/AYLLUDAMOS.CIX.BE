<?php

use Faker\Generator as Faker;
use App\Persona;

$factory->define(App\Familiar::class, function (Faker $faker) {

	// Object Persona
    $persona = Persona::where("mail", "asistemas@adylconsulting.com")->first();

    return [
        "nombres"		=> $faker->name,
        "telefono"		=> $faker->e164PhoneNumber,
        "persona_id"	=> $persona->id
    ];
});
