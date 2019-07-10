<?php

use Faker\Generator as Faker;

$factory->define(App\PivotTest::class, function (Faker $faker) {
    return [
        "nombre"    => $faker->name,
        "edad"      => $faker->randomDigit()
    ];
});
