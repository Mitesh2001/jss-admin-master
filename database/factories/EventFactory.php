<?php

use App\Event;
use App\EventType;

$factory->define(Event::class, function ($faker) {
    return [
        'type_id' => EventType::inRandomOrder()->first()->id,
        'comments' => $faker->sentence,
        'happened_at' => $faker->date,
    ];
});
