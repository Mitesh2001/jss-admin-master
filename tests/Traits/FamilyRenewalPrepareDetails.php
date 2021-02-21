<?php

namespace Tests\Traits;

use App\Discipline;
use App\MembershipType;

trait FamilyRenewalPrepareDetails
{

    /**
     * Returns the total amount of discipline
     *
     * @param \App\Family $family
     * @return int total amount of discipline
     **/
    public function getFamilyDisciplinesAmount($family)
    {
        $commonDisciplines = $this->getCommonDisciplineIds($family->individuals);
        $totalDisciplineAmount = 0;

        if (empty($commonDisciplines)) {
            foreach ($family->individuals as $individual) {
                if ($individual->pension_card) {
                    $totalDisciplineAmount += $individual->disciplines->sum('pensioner_price');
                    continue;
                }

                $totalDisciplineAmount += $individual->disciplines->sum('adult_price');
            }

            return $totalDisciplineAmount;
        }

        $totalDisciplineAmount = Discipline::whereIn('id', $commonDisciplines)->sum('family_price');

        foreach ($family->individuals as $individual) {
            if ($individual->pension_card) {
                $totalDisciplineAmount += $individual->disciplines->whereNotIn('id', $commonDisciplines)->sum('pensioner_price');
                continue;
            }

            $totalDisciplineAmount += $individual->disciplines->whereNotIn('id', $commonDisciplines)->sum('adult_price');
        }

        return $totalDisciplineAmount;
    }

    /**
     * Returns the discount of the membership
     *
     * @param \App\Family $family
     * @return int membership discount
     **/
    public function getMembershipDiscount($family)
    {
        $freeFamilyMember = $family->individuals->filter(function ($individual) {
            return $individual->is_committee_member || $individual->is_club_lifetime_member;
        });

        // All member is committee member or club lifetime member
        if ($freeFamilyMember->count() == $family->individuals->count()) {
            return $family->individuals[0]->membership->type->price;
        }

        // Only one regular member in the family
        if ($freeFamilyMember->count() == $family->individuals->count() - 1) {
            $regularMember = $family->individuals->whereNotIn('id', $freeFamilyMember->pluck('id'))->first();
            $membershipTypeId = $regularMember->pension_card ? 3 : 1;

            return $regularMember->membership->type->price - MembershipType::find($membershipTypeId)->price;
        }

        return 0;
    }

    /**
     * Returns the discount of disciplines
     *
     * @param \App\Family
     * @return int
     **/
    public function getDisciplineDiscount($family)
    {
        $disciplines = $family->individuals->pluck('disciplines')->collapse();
        $totalDisciplineDiscount = 0;
        $commonDisciplines = $this->getCommonDisciplineIds($family->individuals);

        if (empty($commonDisciplines)) {
            $disciplines = $disciplines->where('pivot.is_lifetime_member', true);

            foreach ($disciplines as $discipline) {
                $individual = $family->individuals->firstWhere('id', $discipline->pivot->individual_id);

                if ($individual->pension_card) {
                    $totalDisciplineDiscount += $discipline->pensioner_price;
                    continue;
                }

                $totalDisciplineDiscount += $discipline->adult_price;
            }

            return $totalDisciplineDiscount;
        }

        $totalDisciplineDiscount = 0;
        $cDisciplines = Discipline::whereIn('id', $commonDisciplines)->get();

        foreach ($cDisciplines as $discipline) {
            $totalFreeDiscipline = 0;
            $notDiscountableDiscipline = collect([]);

            foreach ($family->individuals as $individual) {
                $familyDiscipline = $individual->disciplines->firstWhere('id', $discipline->id);
                if ($familyDiscipline->pivot->is_lifetime_member) {
                    $totalFreeDiscipline++;
                    continue;
                }

                $notDiscountableDiscipline->push($individual);
            }

            if ($totalFreeDiscipline == $family->individuals->count()) {
                $totalDisciplineDiscount += $discipline->family_price;
                continue;
            }

            if ($totalFreeDiscipline == $family->individuals->count() - 1) {
                if ($notDiscountableDiscipline[0]->pension_card) {
                    $totalDisciplineDiscount += $discipline->family_price - $discipline->pensioner_price;
                    continue;
                }

                $totalDisciplineDiscount += $discipline->family_price - $discipline->adult_price;
            }

            continue;
        }

        $singleDisciplines = $disciplines->where('pivot.is_lifetime_member', true)->whereNotIn('id', $commonDisciplines);
        foreach ($singleDisciplines as $discipline) {
            if ($discipline->pivot->is_lifetime_member) {
                $individual = $family->individuals->firstWhere('id', $discipline->pivot->individual_id);

                if ($individual->pension_card) {
                    $totalDisciplineDiscount += $discipline->pensioner_price;
                    continue;
                }

                $totalDisciplineDiscount += $discipline->adult_price;
            }
        }

        return $totalDisciplineDiscount;
    }

    /**
     * Set common disciplines is lifetime member field
     *
     * @param \App\Family $family
     * @param boolean $isNotDiscountForFirstDiscipline
     * @param boolean $isPensioner
     * @return \App\Family $family
     **/
    public function setCommonDisciplineLifetimeMemberField($family, $isNotDiscountForFirstDiscipline = false, $isPensioner = false)
    {
        $commonDisciplines = $this->getCommonDisciplineIds($family->individuals);
        foreach ($family->individuals as $key => $individual) {
            $disciplines = $individual->disciplines->whereIn('id', $commonDisciplines);

            $isLifetimeMember = true;

            if ($isNotDiscountForFirstDiscipline && $key == 0) {
                $isLifetimeMember = false;

                if ($isPensioner) {
                    $individual->pension_card = true;
                    $individual->save();
                }
            }

            foreach ($disciplines as $discipline) {
                $individual->disciplines()
                    ->updateExistingPivot($discipline->id, ['is_lifetime_member' => $isLifetimeMember])
                ;
            }
        }

        return $family->load('individuals', 'individuals.membership', 'individuals.membership.type', 'individuals.ssaa', 'individuals.disciplines');
    }
}
