<?php

namespace App\Traits;

use App\Discipline;
use App\MembershipType;

trait PreparesReceiptItems
{
    /**
     * Returns the list of receipt items.
     *
     * @return array list of items
     **/
    public function getReceiptItems($renewal)
    {
        $membershipItem = $this->getMembershipDetails($renewal);

        $disciplineItems = $this->getDisciplinesDetails($renewal);

        $discountItems = $this->getDiscountDetails($renewal);

        return array_merge($membershipItem, $disciplineItems, $discountItems);
    }

    /**
     * Returns the membership item details.
     *
     * @param \App\Renewal $renewal
     * @return array
     **/
    public function getMembershipDetails($renewal)
    {
        $membershipLabel = $this->individual->getMembershipTypeLabel($renewal->iRenewal->type_id) . ' membership for ';

        return [[
            'receipt_item_code_id' => $renewal->iRenewal->type_id,
            'description' => $membershipLabel . $this->getMemberName($renewal, $this->individual),
            'amount' => $renewal->iRenewal->membership_price,
        ]];
    }

    /**
     * Returns the renewal member name with committee/life member flags.
     *
     * @param \App\Renewal $renewal
     * @return \App\Individual $individual
     * @return mix [] | \App\Discipline | \Illuminate\Support\Collection
     * @return string renewal member name
     **/
    public function getMemberName($renewal, $individual, $disciplines = [])
    {
        if ($renewal->iRenewal->type_id != 2) {
            // Returns the membership member name
            if (empty($disciplines)) {
                return $this->getIndividualName($individual);
            }

            // Returns the discipline member name
            return $this->getDisciplineMemberNameDescription($individual, $disciplines);
        }

        // Returns the family discipline member name
        if (! empty($disciplines) && get_class($disciplines) == 'App\Discipline') {
            return $this->getDisciplineMemberNameDescription($individual, $disciplines);
        }

        $familyMembersName = [];
        foreach ($renewal->iRenewal->familyMembers as $familyMember) {
            // Family membership members name
            if (empty($disciplines)) {
                array_push($familyMembersName, $this->getIndividualName($familyMember));

                continue;
            }

            // Family discipline members name
            $familyMemberDiscipline = $disciplines->firstWhere('pivot.individual_id', $familyMember->id);
            array_push($familyMembersName, $this->getDisciplineMemberNameDescription($familyMember, $familyMemberDiscipline));
        }

        return implode(', ', $familyMembersName);
    }

    /**
     * Returns the member name with the discountable label (Committee member, Life member)
     *
     * @param \App\Individual $individual
     * @return string member name with discountable
     **/
    public function getIndividualName($individual)
    {
        $isCommitteeMemberText = $individual->is_committee_member ? ' (Committee member)' : '';
        $isLifeMemberText = $individual->is_club_lifetime_member ? ' (Life member)' : '';

        return $individual->getName() . $isLifeMemberText . $isCommitteeMemberText;
    }

    /**
     * Returns the discipline member name with the discountable label (Life member)
     *
     * @param \App\Individual $individual
     * @param \App\Discipline $discipline
     * @return string member name with discountable
     **/
    public function getDisciplineMemberNameDescription($individual, $discipline)
    {
        $isLifeTimeMemberText = $discipline->pivot->is_lifetime_member ? ' (Life member)' : '';

        return $individual->getName() . $isLifeTimeMemberText;
    }

    /**
     * Returns the list of disciplines details.
     *
     * @param \App\Renewal $renewal
     * @return array
    **/
    public function getDisciplinesDetails($renewal)
    {
        $disciplines = $renewal->iRenewal->disciplines;
        $disciplinesDetails = [];

        // Returns the disciplines details for the adult and pensioner
        if ($renewal->iRenewal->type_id != 2) {
            foreach ($disciplines as $discipline) {
                $disciplineLabel = $this->individual->getMembershipTypeLabel($renewal->iRenewal->type_id) . ' ' . $discipline->label;

                array_push($disciplinesDetails, [
                    'discipline_id' => $discipline->pivot->discipline_id,
                    'receipt_item_code_id' => $renewal->iRenewal->type_id,
                    'description' => $disciplineLabel . ' for ' . $this->getMemberName($renewal, $this->individual, $discipline),
                    'amount' => $discipline->pivot->price,
                ]);
            }

            return $disciplinesDetails;
        }

        // id column is the discipline_id as this is a many-to-many (pivot) relationship
        $groupedDisciplines = $disciplines->groupBy('id');
        $totalFamilyMember = $renewal->iRenewal->familyMembers->count();
        $familyMembers = $renewal->iRenewal->familyMembers;

        foreach ($groupedDisciplines as $disciplines) {
            // Set discipline details for the Family common discipline
            if ($disciplines->count() == $totalFamilyMember) {
                array_push($disciplinesDetails, [
                    'discipline_id' => $disciplines[0]->pivot->discipline_id,
                    'receipt_item_code_id' => 2, // Family
                    'description' => 'Family ' . $disciplines[0]->label . ' for ' . $this->getMemberName($renewal, $this->individual, $disciplines),
                    'amount' => $disciplines[0]->pivot->price,
                ]);

                continue;
            }

            // Set discipline details for the Family discipline
            foreach ($disciplines as $discipline) {
                $familyMember = $familyMembers->firstWhere('id', $discipline->pivot->individual_id);
                $disciplineType = $familyMember->pension_card ? 'Pensioner ' : 'Adult ';

                array_push($disciplinesDetails, [
                    'discipline_id' => $discipline->pivot->discipline_id,
                    'receipt_item_code_id' => $familyMember->pension_card ? 3 : 1,
                    'description' => $disciplineType . $discipline->label . ' for ' . $this->getMemberName($renewal, $familyMember, $discipline),
                    'amount' => $discipline->pivot->price,
                ]);
            }
        }

        return $disciplinesDetails;
    }

    /**
     * Returns the list of discount items.
     *
     * @param \App\Renewal $renewal
     * @return array list of discount items
     **/
    public function getDiscountDetails($renewal)
    {
        return array_merge(
            $this->getMembershipDiscount($renewal),
            $this->getDisciplineDiscount($renewal)
        );
    }

    /**
     * Returns the membership discount item, if any.
     *
     * @param \App\Renewal $renewal
     * @return array
     **/
    public function getMembershipDiscount($renewal)
    {
        $iRenewal = $renewal->iRenewal;
        $membershipLabel = $this->individual->getMembershipTypeLabel($iRenewal->type_id) . ' membership for ';

        if ($iRenewal->type_id != 2) {
            if ($this->individual->is_committee_member || $this->individual->is_club_lifetime_member) {
                return [[
                    'receipt_item_code_id' => $iRenewal->type_id,
                    'description' => 'Discount - ' . $membershipLabel . $this->getMemberName($renewal, $this->individual),
                    'amount' => '-' . $iRenewal->membership_price,
                ]];
            }

            return [];
        }

        if (
            $iRenewal->familyMembers->count() ==
            $this->getFreeMembershipMember($iRenewal->familyMembers)->count()
        ) {
            return [[
                'receipt_item_code_id' => $iRenewal->type_id,
                'description' => 'Discount - ' . $membershipLabel . $this->getMemberName($renewal, $this->individual),
                'amount' => '-' . $iRenewal->membership_price,
            ]];
        }

        if (
            $this->getFreeMembershipMember($iRenewal->familyMembers)->count() ==
            ($iRenewal->familyMembers->count() - 1)
        ) {
            $paidMembershipMember = $this->getPaidMembershipMember($iRenewal->familyMembers);
            $mType = 'Adult';

            if ($paidMembershipMember->pension_card) {
                $mType = 'Pensioner';
            }

            return [[
                'receipt_item_code_id' => $iRenewal->type_id,
                'description' => 'Discount - ' . $membershipLabel . $this->getMemberName($renewal, $this->individual),
                'amount' => '-' . ($iRenewal->membership_price - MembershipType::where('label', $mType)->first()->price),
            ]];
        }

        return [];
    }

    /**
     * Returns the count of the members with free membership.
     *
     * @param \Illuminate\Support\Collection
     * @return int count of free membership
     **/
    public function getFreeMembershipMember($familyMembers)
    {
        return $familyMembers->filter(function ($familyMember) {
            return $familyMember->pivot->is_committee_member || $familyMember->pivot->is_club_lifetime_member;
        });
    }

    /**
     * Returns the only free member of the family.
     *
     * @param \Illuminate\Support\Collection
     * @return int count of free membership
     **/
    public function getPaidMembershipMember($familyMembers)
    {
        return $familyMembers->first(function ($familyMember) {
            return $familyMember->pivot->is_committee_member == 0 &&
                $familyMember->pivot->is_club_lifetime_member == 0
            ;
        });
    }

    /**
     * Returns the disciplines discount items.
     *
     * @param \App\Renewal $renewal
     * @return array
     **/
    public function getDisciplineDiscount($renewal)
    {
        if ($renewal->iRenewal->type_id != 2) {
            $disciplinesDiscount = [];

            foreach ($renewal->iRenewal->disciplines as $discipline) {
                if (
                    $discipline->pivot->is_lifetime_member &&
                    $discipline->pivot->price > 0
                ) {
                    $description = 'Discount - ' . $discipline->label . ' for ' . $this->getDisciplineMemberNameDescription($this->individual, $discipline)
                    ;

                    array_push($disciplinesDiscount, [
                        'discipline_id' => $discipline->pivot->discipline_id,
                        'receipt_item_code_id' => $renewal->iRenewal->type_id,
                        'description' => $description,
                        'amount' => '-' . $discipline->pivot->price,
                    ]);
                }
            }

            return $disciplinesDiscount;
        }

        // id column is the discipline_id as this is a many-to-many (pivot) relationship
        $groupedDisciplines = $renewal->iRenewal->disciplines->groupBy('id');
        $totalFamilyMember = $renewal->iRenewal->familyMembers->count();
        $familyMembers = $renewal->iRenewal->familyMembers;
        $disciplinesDiscountItems = [];

        foreach ($groupedDisciplines as $disciplines) {
            // Set discipline details for the Family common discipline
            if ($disciplines->count() == $totalFamilyMember) {
                $freeMembers = $this->getFreeMembers($disciplines);
                $description = 'Discount - ' . $disciplines[0]->label . ' for ' . $this->getMemberName($renewal, $this->individual, $disciplines);

                if (
                    $freeMembers->count() == $disciplines->count() &&
                    $disciplines[0]->pivot->price > 0
                ) {
                    array_push($disciplinesDiscountItems, [
                        'discipline_id' => $disciplines[0]->pivot->discipline_id,
                        'receipt_item_code_id' => 2, // Family Disciplines
                        'description' => $description,
                        'amount' => '-' . $disciplines[0]->pivot->price,
                    ]);

                    continue;
                }

                if ($freeMembers->count() == ($disciplines->count() - 1)) {
                    $paidMemberRecord = $this->getPaidMember($disciplines);
                    $paidMemberDetails = $familyMembers->firstWhere('id', $paidMemberRecord->pivot->individual_id);
                    $disciplineField = 'adult_price';

                    if ($paidMemberDetails->pivot->is_pensioner) {
                        $disciplineField = 'pensioner_price';
                    }

                    $amount = $disciplines[0]->pivot->price - Discipline::find($paidMemberRecord->id)->$disciplineField;

                    if ($amount > 0) {
                        array_push($disciplinesDiscountItems, [
                            'discipline_id' => $disciplines[0]->pivot->discipline_id,
                            'receipt_item_code_id' => 2, // Family
                            'description' => $description,
                            'amount' => '-' . $amount,
                        ]);
                    }

                    continue;
                }
            }

            foreach ($disciplines as $discipline) {
                if (
                    $discipline->pivot->is_lifetime_member &&
                    $discipline->pivot->price > 0
                ) {
                    $familyMember = $familyMembers->firstWhere('id', $discipline->pivot->individual_id);

                    $disciplineField = 'adult_price';
                    if ($familyMember->pension_card) {
                        $disciplineField = 'pensioner_price';
                    }

                    $amount = Discipline::find($discipline->pivot->discipline_id)->$disciplineField;

                    array_push($disciplinesDiscountItems, [
                        'discipline_id' => $discipline->pivot->discipline_id,
                        'receipt_item_code_id' => $familyMember->pension_card ? 3 : 1,
                        'description' => 'Discount - ' . $discipline->label . ' for ' . $this->getMemberName($renewal, $familyMember, $discipline),
                        'amount' => '-' . $amount,
                    ]);
                }
            }
        }

        return $disciplinesDiscountItems;
    }

    /**
     * Returns the free member records for the specified discipline.
     *
     * @param \Illuminate\Support\Collection $disciplines
     * @return \Illuminate\Support\Collection $disciplines
     **/
    public function getFreeMembers($disciplines)
    {
        return $disciplines->filter(function ($discipline) {
            return $discipline->pivot->is_lifetime_member;
        });
    }

    /**
     * Returns the only paid member of the discipline.
     *
     * @param \Illuminate\Support\Collection $disciplines
     * @return \Illuminate\Support\Collection $disciplines
     **/
    public function getPaidMember($disciplines)
    {
        return $disciplines->first(function ($discipline) {
            return ! $discipline->pivot->is_lifetime_member;
        });
    }
}
