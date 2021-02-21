<?php

namespace App\Traits;

use Carbon\Carbon;

trait IndividualLaratables
{
    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesCustomName($individual)
    {
        return $individual->first_name . ' ' . $individual->surname;
    }

    /**
     * Specify additional conditions for the query, if any.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        $query = $query->with(['membership.type']);

        if (! session('individuals_filter') || session('individuals_filter') == 'active_only') {
            return $query->whereHas('membership', function ($query) {
                $query->where('status', 1);
            });
        }

        if (session('individuals_filter') == 'inactive_only') {
            return $query->whereHas('membership', function ($query) {
                $query->where('status', 0);
            });
        }

        return $query;
    }

    /**
     * Returns the name of the table column to be used for sorting when name column is selected.
     *
     * @return string
     */
    public static function laratablesOrderName()
    {
        return 'first_name';
    }

    /**
     * Adds the condition for searching the name of the individual in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchName($query, $searchValue)
    {
        return $query->orWhere('first_name', 'like', $searchValue)
            ->orWhere('surname', 'like', $searchValue)
            ->orWhereRaw('CONCAT(first_name, " ", surname) like ' . "'%" . $searchValue . "%'")
        ;
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['first_name', 'surname'];
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesCustomAction($individual)
    {
        $isEligibleForRenewal = false;
        if ($individual->membership) {
            $expiryYear = Carbon::createFromFormat('Y-m-d', $individual->membership->expiry)->year;

            if ($expiryYear == now()->year && $individual->membership->status) {
                $isEligibleForRenewal = true;
            }
        }

        return view('admin.individuals.includes.index_action', compact('individual', 'isEligibleForRenewal'))->render();
    }

    /**
     * Returns the Membership type column value for datatables.
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesMembershipType($individual)
    {
        return $individual->getType() ?: 'N/A';
    }

    /**
     * Returns the ssaa status column value for datatables.
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesMembershipStatus($individual)
    {
        return optional($individual->membership)->status ? 'Active' : 'Inactive';
    }

    /**
     * Returns the url to the edit page of the individual.
     *
     * @param \App\Individual $individual
     * @return array
     */
    public static function laratablesRowData($individual)
    {
        return [
            'edit-url' => route('admin.individuals.edit', ['individual' => $individual->id]),
        ];
    }

    /**
     * Eager load ID Card of the individual for displaying in the datatables.
     *
     * @return callable
     */
    public static function laratablesIdCardRelationQuery()
    {
        return function ($query) {
            $query->with('idCard');
        };
    }
}
