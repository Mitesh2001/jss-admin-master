<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class IdCard extends Model
{
    /**
     * Get the individual of the id card.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual', 'individual_id');
    }

    /**
     * Add query conditions and load relations
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return $query->whereNotNull('printed_at')->with([
            'individual',
            'individual.ssaa',
            'individual.membership',
            'individual.branchCode'
        ]);
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['individual_id'];
    }

    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\IdCard $idCard
     * @return string
     */
    public static function laratablesCustomName($idCard)
    {
        return $idCard->individual->first_name.' '.$idCard->individual->surname;
    }

    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\IdCard $idCard
     * @return string
     */
    public static function laratablesCustomBranchCode($idCard)
    {
        return $idCard->individual->branchCode->label;
    }

    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\IdCard $idCard
     * @return string
     */
    public static function laratablesCustomSsaaNumber($idCard)
    {
        return $idCard->individual->ssaa->ssaa_number;
    }

    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\IdCard $idCard
     * @return string
     */
    public static function laratablesCustomMembershipStatus($idCard)
    {
        return optional($idCard->individual->membership)->status ? 'Active' : 'Inactive';
    }

    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\IdCard $idCard
     * @return string
     */
    public static function laratablesCustomMembershipType($idCard)
    {
        return $idCard->individual->getType() ?: 'N/A';
    }

    /**
     * Adds the condition for searching the name of the individual in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchName($query, $searchValue)
    {
        return $query->orWhereHas('individual', function ($query) use ($searchValue) {
            $query->where('first_name', 'like', '%'. $searchValue. '%')
                ->orWhere('surname', 'like', '%'. $searchValue. '%')
            ;
        });
    }

    /**
     * Adds the condition for searching the branch code of the individual in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchBranchCode($query, $searchValue)
    {
        return $query->orWhereHas('individual', function ($query) use ($searchValue) {
            $query->whereHas('branchCode', function ($query) use ($searchValue) {
                $query->where('label', 'like', '%'. $searchValue. '%');
            });
        });
    }

    /**
     * Adds the condition for searching the ssaa number of the individual in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchSsaaNumber($query, $searchValue)
    {
        return $query->orWhereHas('individual', function ($query) use ($searchValue) {
            $query->whereHas('ssaa', function ($query) use ($searchValue) {
                $query->where('ssaa_number', 'like', '%'. $searchValue. '%');
            });
        });
    }

    /**
     * Adds the condition for searching the membership type of the individual in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchMembershipType($query, $searchValue)
    {
        return $query->orWhereHas('individual', function ($query) use ($searchValue) {
            $query->whereHas('membership', function ($query) use ($searchValue) {
                    $query->whereHas('type', function ($query) use ($searchValue) {
                        $query->where('label', 'like', '%'. $searchValue. '%');
                    });
                })
            ;
        });
    }
}
