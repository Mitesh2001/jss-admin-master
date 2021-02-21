<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Individual;
use App\RangeOfficer;

class IndividualRangeOfficerController extends Controller
{
    /**
     * Add range officer for the individual.
     *
     * @param  \App\Individual $individual
     * @return array
     */
    public function store(Individual $individual)
    {
        $validatedData = request()->validate(RangeOfficer::individualValidationRules());
        $officer = RangeOfficer::where('discipline_id', $validatedData['discipline_id'])
            ->where('individual_id', $individual->id)
            ->count()
        ;

        if ($officer) {
            return [
                'type' => 'error',
                'message' => 'The discipline has already been taken.',
            ];
        }

        $officer = $individual->officers()->create($validatedData);

        return [
            'type' => 'success',
            'message' => 'Range officer accreditation added successfully.',
            'data' => $officer,
        ];
    }

    /**
     * Update specified range officer of the individual.
     *
     * @param \App\Individual $individual
     * @param \App\RangeOfficer $rangeOfficer
     * @return array
     */
    public function update(Individual $individual, RangeOfficer $rangeOfficer)
    {
        $validatedData = request()->validate(RangeOfficer::individualValidationRules());

        $officerCount = RangeOfficer::where('discipline_id', $validatedData['discipline_id'])
            ->where('individual_id', $individual->id)
            ->where('id', '!=', $rangeOfficer->id)
            ->count()
        ;

        if ($officerCount) {
            return [
                'type' => 'error',
                'message' => 'The discipline has already been taken.',
            ];
        }

        $rangeOfficer->discipline_id = $validatedData['discipline_id'];
        $rangeOfficer->added_date = $validatedData['added_date'];
        $rangeOfficer->save();

        return [
            'type' => 'success',
            'message' => 'Range officer accreditation updated successfully.',
            'data' => $rangeOfficer,
        ];
    }

    /**
     * Deletes the range office from individual.
     *
     * @param int $individualId
     * @param \App\RangeOfficer $rangeOfficer
     * @return void
     */
    public function destroy($individualId, RangeOfficer $rangeOfficer)
    {
        $rangeOfficer->delete();

        return [
            'type' => 'success',
            'message' => 'Range officer accreditation deleted successfully.',
        ];
    }
}
