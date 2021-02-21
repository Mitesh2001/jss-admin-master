<?php

use App\BranchCode;
use App\Discipline;
use App\Event;
use App\Gender;
use App\Individual;
use App\IndividualInsurance;
use App\IndividualQualification;
use App\IndividualRenewal;
use App\RenewalRunEntity;
use App\State;
use App\Suburb;
use Carbon\Carbon;

$factory->define(Individual::class, function ($faker) {
    return [
        'surname' => $faker->word,
        'middle_name' => $faker->firstName,
        'first_name' => $faker->lastName,
        'date_of_birth' => $faker->date,
        'gender_id' => Gender::inRandomOrder()->first()->id,
        'email_address' => $faker->safeEmail,
        'mobile_number' => '04' . rand(11111111, 99999999),
        'phone_number' => '04' . rand(11111111, 99999999),
        'occupation' => $faker->jobTitle,
        'address_line_1' => $faker->streetName,
        'address_line_2' => $faker->streetAddress,
        'state_id' => $stateId = State::where('label', 'WA')->first()->id,
        'suburb_id' => Suburb::where('state_id', $stateId)->inRandomOrder()->first()->id,
        'post_code' => '6' . rand(111, 999),
        'pension_card' => $faker->boolean && $faker->boolean,
        'is_committee_member' => $faker->boolean && $faker->boolean,
        'is_club_lifetime_member' => $faker->boolean && $faker->boolean,
        'branch_code_id' => BranchCode::inRandomOrder()->first()->id,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ];
});

$factory->afterCreating(Individual::class, function ($individual, $faker) {
    if (config('app.env') != 'testing') {
        $individual->ssaa()->save(factory(App\IndividualSsaa::class)->make());

        $individualMembership = factory(App\IndividualMembership::class)->make();

        $individualMembership->membership_number = 'W02 ' . $individual->ssaa->ssaa_number;
        $individualMembership->type_id = $individual->pension_card ? 3 : 1;

        $individual->membership()->save($individualMembership);

        if ($individualMembership->status == 1) {
            RenewalRunEntity::create([
                'renewal_run_id' => 1,
                'individual_id' => $individual->id,
            ]);
        }

        $disciplines = Discipline::inRandomOrder()->take(rand(1, 3))->get();
        $individualDisciplines = [];

        foreach ($disciplines as $discipline) {
            $individualDisciplines[$discipline->id] = [
                'is_lifetime_member' => $faker->boolean,
                'registered_at' => $faker->dateTimeBetween($startDate = '-6 months', $endDate = 'now'),
                'approved_at' => $faker->dateTimeBetween($startDate = '-6 months', $endDate = 'now'),
            ];
        }

        $individual->disciplines()->attach($individualDisciplines);

        $individual->events()->saveMany(factory(Event::class, 3)->make());
    }
});
