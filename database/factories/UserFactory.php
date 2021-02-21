<?php

use App\Discipline;
use App\Individual;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->username,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'admin', function ($faker) {
    return [
        'type' => 1,
    ];
});

$factory->state(User::class, 'captain', function ($faker) {
    return [
        'type' => 2,
    ];
});


$factory->afterCreating(User::class, function ($user, $faker) {
    $individual = Individual::inRandomOrder()->first();

    $user->individual_id = $individual->id;
    $user->save();

    if ($user->type == 2) {
        $disciplineIds = Discipline::inRandomOrder()
            ->take(rand(1, 3))
            ->get()
            ->pluck('id')
            ->toArray()
        ;

        $user->disciplines()->attach($disciplineIds);
    }
});
