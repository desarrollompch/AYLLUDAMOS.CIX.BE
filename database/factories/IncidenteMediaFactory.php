<?php

use Faker\Generator as Faker;
use App\Incidente;

$factory->define(App\IncidenteMedia::class, function (Faker $faker) {


	$incidente = Incidente::first();

    return [
        "incidente_id" 			=> $incidente->id,
        "tipo_media"			=> "imagen",
        "incidente_media_name"	=> $faker->text,
        "incidente_media_url"	=> $faker->imageUrl
    ];
});
