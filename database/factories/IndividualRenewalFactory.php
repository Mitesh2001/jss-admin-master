<?php

use App\Family;
use App\IndividualRenewal;
use App\Jobs\ProcessRenewal;
use App\MembershipType;
use App\RenewalRun;
use App\RenewalRunEntity;
use Carbon\Carbon;
use Illuminate\Support\Str;

$factory->define(IndividualRenewal::class, function ($faker) {
    return [
        //
    ];
});

$factory->state(IndividualRenewal::class, 'offline', function ($faker) {
    return [
        'payment_type' => 1,
    ];
});

$factory->state(IndividualRenewal::class, 'online', function ($faker) {
    return [
        'payment_type' => 2,
        'transaction_no' => Str::random(27),
    ];
});

$factory->afterCreating(IndividualRenewal::class, function ($iRenewal, $faker) {
    if (config('app.env') != 'testing') {
        $individualIds = IndividualRenewal::query()
            ->whereNotNull('individual_id')
            ->get(['individual_id'])
            ->toArray()
        ;

        $renewalRunEntity = RenewalRunEntity::query()
            ->with('individual', 'individual.ssaa')
            ->whereNotIn('individual_id', $individualIds)
            ->first()
        ;

        if (! $renewalRunEntity) {
            return;
        }

        $individual = $renewalRunEntity->individual;

        $membershipType = MembershipType::query()
            ->where('label', $individual->getMembershipTypeLabel($individual->membership->type_id))
            ->first()
        ;

        $iRenewal->fill([
            'first_name' => $individual->first_name,
            'middle_name' => $individual->middle_name,
            'surname' => $individual->surname,
            'email_address' => $individual->email_address,
            'date_of_birth' => $individual->date_of_birth,
            'mobile_number' => $individual->mobile_number,
            'phone_number' => $individual->phone_number,
            'address_line_1' => $individual->address_line_1,
            'address_line_2' => $individual->address_line_2,
            'suburb_id' => $individual->suburb_id,
            'state_id' => $individual->state_id,
            'post_code' => $individual->post_code,
            'gender_id' => $individual->gender_id,
            'membership_no' => $individual->getMembershipNumber(),
            'type_id' => $individual->membership->type_id,
            'ssaa_expiry' => Carbon::createFromFormat('Y-m-d', $individual->ssaa->ssaa_expiry)->addYear()->format('Y-m-d'),
            'amount' => $amount = rand(100, 300),
            'discount' => rand(100, $amount),
            'renewal_applier_full_name' => $faker->name,
            'membership_price' => $membershipType->price,
        ]);

        $renewalEntry = $individual->renewalEntries()->save($iRenewal);

        $individual->renewals()->create([
            'individual_renewal_id' => $renewalEntry->id,
            'renewal_run_id' => 1,
        ]);

        // Add disciplines for the adult and pensioner
        if ($iRenewal->type_id != 2) {
            $disciplines = $iRenewal->individual->disciplines;

            foreach ($disciplines as $discipline) {
                $fieldName = strtolower($iRenewal->individual->getMembershipTypeLabel($iRenewal->type_id)) . '_price';

                $iRenewal->disciplines()->attach([
                    $discipline->id => [
                        'individual_id' => $discipline->pivot->individual_id,
                        'is_lifetime_member' => $discipline->pivot->is_lifetime_member,
                        'price' => $discipline->$fieldName,
                    ]
                ]);
            }
        }

        // Add disciplines and family member for the Family
        if ($iRenewal->type_id == 2) {
            if (! $iRenewal->individual->family_id) {
                return;
            }

            $family = Family::query()
                ->with(['individuals', 'individuals.disciplines'])
                ->find($iRenewal->individual->family_id)
            ;

            foreach ($family->individuals as $individual) {
                $iRenewal->familyMembers()->attach([
                    $individual->id => [
                        'is_pensioner' => $individual->pension_card,
                        'is_committee_member' => $individual->is_committee_member,
                        'is_club_lifetime_member' => $individual->is_club_lifetime_member,
                    ]
                ]);

                foreach ($individual->disciplines as $discipline) {
                    $iRenewal->disciplines()->attach([
                        $discipline->id => [
                            'individual_id' => $discipline->pivot->individual_id,
                            'is_lifetime_member' => $discipline->pivot->is_lifetime_member,
                            'price' => $discipline->family_price,
                        ]
                    ]);
                }
            }
        }

        // Process renewal
        $renewalRun = RenewalRun::select('id', 'start_date')
            ->findOrFail($renewalRunEntity->renewal_run_id)
        ;

        config(['services.sparkpost.secret' => '']);
        $transactionFee = $iRenewal->payment_type == 2 ? mt_rand() / mt_getrandmax() : 0;

        ProcessRenewal::dispatch($iRenewal->individual, $renewalRun, $transactionFee);
    }
});
