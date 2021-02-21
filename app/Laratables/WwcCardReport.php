<?php

namespace App\Laratables;

use Carbon\Carbon;

class WwcCardReport
{
    /**
     * Returns the custom individual name column for datatables.
     *
     * @param \App\Individual
     * @return string
     */
    public static function laratablesCustomName($individual)
    {
        return view('admin.reports.wwc_cards.includes.index_name', compact('individual'))->render();
    }

    /**
     * Returns the custom status column for datatables.
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesCustomStatus($individual)
    {
        $status = '<span class="text-danger">Expired</span>';

        $date = Carbon::createFromFormat('Y-m-d', $individual->wwc_expiry_date);
        if ($date->gt(now())) {
            $textColor = 'success';

            if ($date->lt(now()->addDays(10))) {
                $textColor = 'warning';
            }

            $status = '<span class="text-'.$textColor.'">Expires in '.$date->diffInDays(now()).' Days</span>';
        }

        return $status;
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
     * Specify conditions for the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return $query->whereNotNull('wwc_card_number')
            ->whereNotNull('wwc_expiry_date')
            ->whereHas('membership', function ($query) {
                $query->where('status', 1);
            })
        ;
    }
}
