<?php

use App\IndividualMembership;
use App\MembershipType;
use Faker\Generator as Faker;

$factory->define(IndividualMembership::class, function (Faker $faker) {
    return [
        'join_date' => $faker->date,
        'status' => $faker->boolean,
        'type_id' => MembershipType::inRandomOrder()->first()->id,
        'expiry' => $faker->date,
        'notes' => $faker->text,
    ];
});
