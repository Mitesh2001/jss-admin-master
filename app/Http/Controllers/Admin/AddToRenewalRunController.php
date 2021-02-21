<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Individual;
use App\RenewalRun;
use App\RenewalRunEntity;

class AddToRenewalRunController extends Controller
{
    /**
     * Adds all the active individuals to the specified renewal run.
     *
     * @param int Id of the renewal run
     * @return void
     */
    public function active($renewalRunId)
    {
        $individualIds = Individual::query()
            ->whereHas('membership', function ($query) {
                $query->where('status', 1)->whereYear('expiry', now()->year);
            })
            ->get(['id'])
            ->pluck('id')
        ;

        $renewalRunEntities = $this->prepareRenewalRunEntities($renewalRunId, $individualIds);
        RenewalRunEntity::insert($renewalRunEntities);
    }

    /**
     * Adds the specified individual to the current renewal run.
     *
     * @param int Id of the individual
     * @return void
     */
    public function single($individualId)
    {
        $renewalRunId = RenewalRun::where('status', true)->value('id');

        $renewalRunEntity = RenewalRunEntity::query()
            ->where('individual_id', $individualId)
            ->where('renewal_run_id', $renewalRunId)
            ->count()
        ;
        if ($renewalRunEntity) {
            abort(403, 'Individual already added.');
        }

        $renewalRunEntities = $this->prepareRenewalRunEntities($renewalRunId, [$individualId]);
        RenewalRunEntity::insert($renewalRunEntities);
    }

    /**
     * Prepares an array to insert multiple records in the renewal_run_entities table.
     *
     * @param int Id of the renewal run
     * @param array Ids of the individuals
     * @return array
     */
    public function prepareRenewalRunEntities($renewalRunId, $individualIds)
    {
        $data = [];

        foreach ($individualIds as $individualId) {
            $data[] = [
                'renewal_run_id' => $renewalRunId,
                'individual_id' => $individualId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $data;
    }

    /**
     * Removes the individual from the renewal run.
     *
     * @param int Id of the renewal run
     * @param int Id of the individual
     * @return void
     */
    public function remove($renewalRunId, $individualId)
    {
        RenewalRunEntity::where('renewal_run_id', $renewalRunId)
            ->where('individual_id', $individualId)
            ->delete()
        ;
    }
}
