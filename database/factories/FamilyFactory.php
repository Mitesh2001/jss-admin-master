<?php

use App\Family;
use App\Individual;
use App\IndividualMembership;
use Faker\Generator as Faker;

$factory->define(Family::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterCreating(Family::class, function ($family, $faker) {
    if (config('app.env') != 'testing') {
        $individualIds = Individual::query()
            ->whereNull('family_id')
            ->whereHas('membership', function ($query) {
                $query->where('status', 1);
            })
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->pluck('id')
        ;

        Individual::whereIn('id', $individualIds)->update(['family_id' => $family->id]);

        IndividualMembership::whereIn('id', $individualIds)->update(['type_id' => 2]);
    }
});
