<?php

namespace App\Laratables;

class IdCardLaratables
{
    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\Individual
     * @return string
     */
    public static function laratablesCustomName($individual)
    {
        return $individual->first_name . ' ' . $individual->surname;
    }

    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\Individual
     * @return string
     */
    public static function laratablesCustomPrintRun($individual)
    {
        return optional($individual->idCard)->is_added_for_printrun ?
            '<span class="badge badge-success">Yes</span>' :
            '<span class="badge badge-danger">No</span>'
        ;
    }

    /**
     * Specify additional conditions for the query, if any.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return $query->with(['idCard'])
            ->whereHas('idCard')
            ->when(session('id_card_queue_filter') == 'queued_only', function ($query) {
                $query->whereHas('idCard', function ($query) {
                    $query->where('is_added_for_printrun', true);
                });
            })
            ->when(session('id_card_queue_filter') == 'non_queued_only', function ($query) {
                $query->whereHas('idCard', function ($query) {
                    $query->where('is_added_for_printrun', false);
                });
            })
        ;
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
     * Returns the select action column html for datatables
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesCustomSelectAction($individual)
    {
        return view('admin.id_cards.includes.select_index_action', compact('individual'))->render();
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesCustomAction($individual)
    {
        return view('admin.id_cards.includes.index_action', compact('individual'))->render();
    }
}
