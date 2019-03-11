<?php

use Faker\Generator as Faker;

$factory->define(App\Tab::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'lat' => $faker->randomFloat('6',-16,-17),
        'lng' => $faker->randomFloat('6',-68,-67),
        'connections' => $faker->randomDigitNotNull,
        'busy' => $faker->randomDigitNotNull
    ];
});
