<?php

namespace Tests\Traits;

use App\Family;
use App\Discipline;
use App\Individual;
use App\RenewalRunEntity;

trait CreatesIndividuals
{
    /**
     * Creates and returns an individual record.
     *
     * @param int Type of the individual
     * @param array individual details
     * @param bool is discipline discount
     * @return \App\Individual
     */
    public function createIndividualOf($typeId = 1, $individualDetails = [], $isDisciplineDiscount = false)
    {
        $individual = factory(Individual::class)->create(array_merge([
            'pension_card' => false,
            'is_committee_member' => false,
            'is_club_lifetime_member' => false,
        ], $individualDetails));

        $individual->ssaa()->create();

        $individual->membership()->create([
            'status' => 1,
            'type_id' => $typeId,
        ]);

        RenewalRunEntity::create([
            'renewal_run_id' => 1,
            'individual_id' => $individual->id,
        ]);

        $disciplines = Discipline::inRandomOrder()->take(3)->get();
        $individualDisciplines = [];
        $isLifetimeMember = false;

        foreach ($disciplines as $discipline) {
            $isLifetimeMember = $isDisciplineDiscount && ! $isLifetimeMember ? true : false;

            $individualDisciplines[$discipline->id] = [
                'is_lifetime_member' => $isLifetimeMember,
                'registered_at' => now(),
                'approved_at' => now(),
            ];
        }

        $individual->disciplines()->attach($individualDisciplines);

        return $individual->load(['membership', 'ssaa', 'disciplines', 'membership.type']);
    }

    /**
     * Returns the family
     *
     * @param array individual details
     * @param array first member individual details
     * @param boolean $isDisciplineDiscount
     * @return \App\Family
     **/
    public function createFamily(
        $individualDetails = [],
        $firstFamilyMemberDetails = [],
        $isCommonDiscipline = false,
        $isDisciplineDiscount = false
    ) {
        $family = Family::create([]);
        $individualDetails = array_merge([
            'family_id' => $family->id
        ], $individualDetails);

        $this->createIndividualOf($typeId = 2, array_merge($individualDetails, $firstFamilyMemberDetails), $isDisciplineDiscount);
        $this->createIndividualOf($typeId = 2, $individualDetails, $isDisciplineDiscount);
        $this->createIndividualOf($typeId = 2, $individualDetails, $isDisciplineDiscount);

        $family->load('individuals', 'individuals.membership', 'individuals.membership.type', 'individuals.ssaa', 'individuals.disciplines');

        $commonDisciplineIds = $this->getCommonDisciplineIds($family->individuals);

        if (
            (empty($commonDisciplineIds) && ! $isCommonDiscipline) ||
            (! empty($commonDisciplineIds) && $isCommonDiscipline)
        ) {
            return $family;
        }

        if (empty($commonDisciplineIds) && $isCommonDiscipline) {
            return $this->attachCommonDiscipline($family, $isDisciplineDiscount);
        }

        return $this->detachCommonDisciplines($family, $commonDisciplineIds);
    }

    /**
     * Returns the common discipline ids
     *
     * @param \Illuminate\Support\Collection
     * @return array common discipline ids
     **/
    public function getCommonDisciplineIds($individuals)
    {
        $disciplines = $individuals->pluck('disciplines', 'id');
        $disciplineIndividuals = [];

        foreach ($disciplines as $key => $discipline) {
            $disciplineIndividuals[$key] = $discipline->pluck('id')->toArray();
        }

        return array_values(call_user_func_array('array_intersect', $disciplineIndividuals));
    }

    /**
     * Remove common disciplines
     *
     * @param \App\Family $family
     * @return \App\Family $family
     **/
    public function detachCommonDisciplines($family, $commonDisciplineIds)
    {
        foreach ($family->individuals as $individual) {
            $individual->disciplines()->detach($commonDisciplineIds);
        }

        return $family->load('individuals', 'individuals.membership', 'individuals.membership.type', 'individuals.ssaa', 'individuals.disciplines');
    }

    /**
     * Attach common discipline
     *
     * @param \App\Family $family
     * @param boolean $isDisciplineDiscount
     * @return \App\Family $family
     **/
    public function attachCommonDiscipline($family, $isDisciplineDiscount)
    {
        $commonDisciplineId = $family->individuals[0]->disciplines[0]->id;

        foreach ($family->individuals as $individual) {
            if (! $individual->disciplines->where('id', $commonDisciplineId)->count()) {
                $individual->disciplines()->attach($commonDisciplineId, [
                    'is_lifetime_member' => $isDisciplineDiscount,
                    'registered_at' => now(),
                    'approved_at' => now(),
                ]);
            }
        }

        return $family->load('individuals', 'individuals.membership', 'individuals.membership.type', 'individuals.ssaa', 'individuals.disciplines');
    }
}
