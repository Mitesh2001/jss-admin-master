<?php

use App\CalendarEvent;
use App\Discipline;
use App\Individual;
use Faker\Generator as Faker;

$factory->define(CalendarEvent::class, function (Faker $faker) {
    return [
        'event_date' => $faker->date,
        'score_type' => rand(1, 2),
        'is_finalised' => $faker->boolean,
    ];
});

$factory->afterMaking(CalendarEvent::class, function ($calendarEvent, $faker) {
    $disciplineId = Discipline::inRandomOrder()->first()->id;

    $calendarEvent->discipline_id = $disciplineId;
});

$factory->afterCreating(CalendarEvent::class, function ($calendarEvent, $faker) {
    $individuals = Individual::inRandomOrder()->limit(3)->get();

    foreach ($individuals as $individual) {
        $calendarEvent->scores()->create([
            'individual_id' => $individual->id,
            'score' => rand(1, 100),
            'score_unit' => $calendarEvent->score_type == 2 ? rand(1, 2) : null,
        ]);
    }
});
