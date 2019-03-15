<?php

use Faker\Generator as Faker;

$factory->define(App\Device::class, function (Faker $faker) {
    $connections = $faker->numberBetween(1,20);
    return [
        'name' => $faker->word,
        'lat' => $faker->latitude(-16.491157,-16.509262),
        'lng' => $faker->longitude(-68.123174,-68.143087),
        'connections' => $connections,
        'busy' => $faker->numberBetween(1,$connections),
        'device_type_id' => \App\DeviceType::all()->random()->id
    ];
});
