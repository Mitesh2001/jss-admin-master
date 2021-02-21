<?php

namespace App\Utilities;

use App\Exceptions\SecurityException;
use App\Renewal;

class RenewalRequestSecurityCheck
{
    /**
     * Throws exception if the request data is not valid.
     *
     * @param \App\Individual $individual
     * @param \App\Family $family
     * @param int $renewalRunId
     * @return void
     *
     * @throws SecurityException
     **/
    public function renewalDataMismatch($individual, $family, $renewalRunId)
    {
        // If second user tries to submit renewal like first member.
        if (
            request('payment_type') != 0 &&
            $this->isFamilyRenewalAlreadySubmittedByOtherMember($family, $renewalRunId)
        ) {
            throw new SecurityException;
        }

        // If user tries to convey that family renewal is already paid.
        if (
            request('type_id') == 2 &&
            request('is_family_renewal_already_paid') &&
            ($family && ! $family->isFamilyRenewalAlreadyPaid($renewalRunId))
        ) {
            throw new SecurityException;
        }

        // Check for valid membership details
        if (
            (request('type_id') == 2 && ! $individual->family_id) ||
            (request('type_id') == 2 && ! optional($family)->individuals) ||
            (request('type_id') == 3 && ! $individual->pension_card)
        ) {
            throw new SecurityException;
        }

        // Check adult and pensioner discipline
        if (request('disciplines')) {
            foreach (request('disciplines') as $key => $disciplineId) {
                if (! $individual->disciplines->contains('id', $disciplineId)) {
                    throw new SecurityException;
                }
            }
        }

        // Check for price mismatch
        list($totalAmount, $discount) = $individual->getRenewalAmount($family);
        if (
            ($family && ! $family->isFamilyRenewalAlreadyPaid($renewalRunId)) &&
            (number_format($totalAmount - $discount, 2) != request('amount') ||
            $discount != request('discount'))
        ) {
            throw new SecurityException;
        }

        if (request('family_member')) {
            foreach (request('family_member') as $key => $memberId) {
                // Check request family member is the family member
                if (! $family->individuals->contains('id', $memberId)) {
                    throw new SecurityException;
                }

                // Check each family member has one discipline
                if (request('family_disciplines')) {
                    foreach ($family->individuals as $individual) {
                        if (! $individual->disciplines) {
                            throw new SecurityException;
                        }
                    }
                }
            }
        }

        if (! request('family_disciplines')) {
            return;
        }

        // User should not be able to register in a discipline for each he/she is not approved/registered already
        foreach (request('family_disciplines') as $key => $disciplineId) {
            $individualIds = explode(',', request('individuals')[$key]);

            foreach ($individualIds as $individualId) {
                $renewalIndividual = $family->individuals->firstWhere('id', $individualId);
                $discipline = $renewalIndividual->disciplines->firstWhere('id', $disciplineId);

                if (! $discipline) {
                    throw new SecurityException;
                }
            }
        }
    }


    /**
     * If second or third member tries to process renewal as first member
     *
     * @param \App\Family $family
     * @param int $renewalRunId
     * @return bool
     */
    public function isFamilyRenewalAlreadySubmittedByOtherMember($family, $renewalRunId)
    {
        if (! optional($family)->individuals) {
            return false;
        }

        return Renewal::query()
            ->where('renewal_run_id', $renewalRunId)
            ->whereIn('individual_id', $family->individuals->pluck('id')->toArray())
            ->exists()
        ;
    }
}
