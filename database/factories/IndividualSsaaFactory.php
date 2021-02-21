<?php

use Faker\Generator as Faker;

$factory->define(App\IndividualSsaa::class, function (Faker $faker) {
    return [
        'ssaa_number' => rand(111111111, 999999999),
        'ssaa_status' => $faker->boolean,
        'ssaa_expiry' => $faker->date,
    ];
});
