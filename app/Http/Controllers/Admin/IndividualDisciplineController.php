<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use App\Http\Controllers\Controller;
use App\Individual;

class IndividualDisciplineController extends Controller
{
    /**
     * Add discipline for the individual.
     *
     * @param  \App\Individual $individual
     * @return array
     */
    public function store(Individual $individual)
    {
        $validatedData = request()->validate(Discipline::individualValidationRules());

        $individual->disciplines()->attach(
            $validatedData['discipline_id'],
            [
                'is_lifetime_member' => $validatedData['is_lifetime_member'],
                'registered_at' => $validatedData['registered_at'],
                'approved_at' => $validatedData['approved_at']
            ]
        );

        return [
            'type' => 'success',
            'message' => 'Discipline added successfully.',
            'data' => $individual->disciplines,
        ];
    }

    /**
     * Update specified discipline of the individual.
     *
     * @param \App\Individual $individual
     * @param int $disciplineId
     * @return array
     */
    public function update(Individual $individual, $disciplineId)
    {
        $validatedData = request()->validate(Discipline::individualValidationRules());

        $individual->disciplines()->updateExistingPivot(
            $validatedData['discipline_id'],
            [
                'is_lifetime_member' => $validatedData['is_lifetime_member'],
                'registered_at' => $validatedData['registered_at'],
                'approved_at' => $validatedData['approved_at']
            ]
        );

        $validatedData['i_discipline_id'] = $disciplineId;

        return [
            'type' => 'success',
            'message' => 'Discipline updated successfully.',
            'data' => $validatedData,
        ];
    }

    /**
     * Deletes/detaches the discipline from individual.
     *
     * @param \App\Individual $individual
     * @return void
     */
    public function destroy(Individual $individual)
    {
        $individual->disciplines()->detach(request('discipline_id'));

        return [
            'type' => 'success',
            'message' => 'Discipline deleted successfully.',
        ];
    }
}
