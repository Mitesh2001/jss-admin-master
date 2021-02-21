<?php

namespace App\Traits;

use App\Family;
use App\Individual;
use App\IndividualRenewal;
use Carbon\Carbon;

trait ApprovesMember
{
    /**
     * Approves the renewal.
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @param \App\Individual $individual
     * @param boolean $isPrintCard
     * @return void
     **/
    public function approveRenewal($individualRenewal, $individual, $isPrintCard = false)
    {
        $individualRenewal->renewal->approved = true;
        $individualRenewal->renewal->save();

        $individual->membership->type_id = $individualRenewal->type_id;
        $individual->membership->status = 1;
        $individual->membership->expiry = (new Carbon($individualRenewal->renewal->renewalRun->start_date))->endOfYear()->toDateString();
        $individual->membership->save();

        $individual->updateDetails($individualRenewal);

        if ($individualRenewal->familyMembers->isNotEmpty()) {
            $this->approveFamilyMembers($individualRenewal, $isPrintCard);
        } elseif ($isPrintCard && $individual->idCard()->count() == 0) {
            $individual->idCard()->create();
        }

        if ($individualRenewal->payment_type == 0) {
            return;
        }

        $this->updateDisciplines($individualRenewal);

        if ($individualRenewal->type_id == 2) {
            $this->removeExtraFamilyMembers($individualRenewal);
        }
    }

    /**
     * Updates disciplines details.
     *
     * @param \App\IndividualRenewal $iRenewal
     * @return void
     **/
    public function updateDisciplines($iRenewal)
    {
        if ($iRenewal->type_id != 2) {
            if ($iRenewal->disciplines->count() != $iRenewal->individual->disciplines->count()) {
                $removedDisciplines = $iRenewal->individual->disciplines->pluck('id')->diff($iRenewal->disciplines->pluck('id'));

                $iRenewal->individual->disciplines()->detach($removedDisciplines->toArray());
            }

            return;
        }

        $this->updateFamilyMembersDisciplines($iRenewal);
    }

    /**
     * Updates Family members disciplines.
     *
     * @param \App\IndividualRenewal $iRenewal
     * @return void
     **/
    public function updateFamilyMembersDisciplines($iRenewal)
    {
        foreach ($iRenewal->familyMembers as $familyMember) {
            $renewalDisciplines = $iRenewal->disciplines
                ->where('pivot.individual_id', $familyMember->id)
                ->pluck('id')
                ->toArray()
            ;

            $removedDisciplines = $familyMember->disciplines->pluck('id')->diff($renewalDisciplines)->toArray();

            if (! empty($removedDisciplines)) {
                $familyMember->disciplines()->detach($removedDisciplines);
            }
        }
    }

    /**
     * Removes old family members which have been removed during renewal.
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @return void
     **/
    public function removeExtraFamilyMembers($individualRenewal)
    {
        $totalRenewalFamilyMembers = $individualRenewal->familyMembers->count();
        $family = Family::withCount('individuals')->find($individualRenewal->individual->family_id);

        if ($family->individuals_count != $totalRenewalFamilyMembers) {
            $renewalFamilyMemberIds = $individualRenewal->familyMembers->pluck('id')->toArray();

            Individual::query()
                ->whereNotIn('id', $renewalFamilyMemberIds)
                ->where('family_id', $individualRenewal->individual->family_id)
                ->update(['family_id' => null])
            ;
        }
    }

    /**
     * Approves the family members
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @param boolean $isPrintCard
     * @return void
     **/
    public function approveFamilyMembers($individualRenewal, $isPrintCard)
    {
        $familyMemberIds = $individualRenewal->familyMembers->pluck('id')->toArray();
        $renewalRunId = $individualRenewal->renewal->renewal_run_id;

        $individualRenewals = IndividualRenewal::query()
            ->with(['renewal', 'renewal.renewalRun', 'individual', 'individual.membership'])
            ->whereIn('individual_id', $familyMemberIds)
            ->whereHas('renewal', function ($query) use ($renewalRunId) {
                $query->where('renewal_run_id', $renewalRunId);
            })
            ->get()
        ;

        foreach ($individualRenewals as $iRenewal) {
            $iRenewal->renewal->approved = true;
            $iRenewal->renewal->save();

            $iRenewal->individual->membership->type_id = $iRenewal->type_id;
            $iRenewal->individual->membership->status = 1;
            $iRenewal->individual->membership->expiry = (new Carbon($iRenewal->renewal->renewalRun->start_date))->endOfYear()->toDateString();
            $iRenewal->individual->membership->save();

            $iRenewal->individual->updateDetails($iRenewal);

            if ($isPrintCard) {
                $iRenewal->individual->idCard()->create();
            }
        }
    }
}
