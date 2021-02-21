<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Family extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * Returns the action column html for datatables
     *
     * @param \App\Family $family
     * @return string
     */
    public static function laratablesCustomAction($family)
    {
        return view('admin.families.action', compact('family'))->render();
    }

    /**
     * Returns the Custom individual count column for datatables.
     *
     * @param \App\Family $family
     * @return int
     */
    public static function laratablesCustomIndividuals($family)
    {
        return $family->individuals->map(function ($individual, $key) {
            $url = route('admin.individuals.edit', ['individual' => $individual->id]);

            return '<a href="' . $url . '" target="_blank">' . $individual->getName() . '</a>';
        })->implode(', ');
    }

    /**
     * Adds the condition for searching the individual of the family in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchIndividuals($query, $searchValue)
    {
        return $query->orWhereHas('individuals', function ($query) use ($searchValue) {
            $query->where('first_name', 'like', '%' . $searchValue . '%')
                ->orWhere('surname', 'like', '%' . $searchValue . '%')
            ;
        });
    }

    /**
     * Specify additional conditions for the query, if any.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return $query->with(['individuals:id,first_name,surname,family_id']);
    }

    /**
     * Returns the url to the edit page of the Family.
     *
     * @param \App\Family $family
     * @return array
     */
    public static function laratablesRowData($family)
    {
        return [
            'edit-url' => route('admin.families.edit', ['family' => $family->id]),
        ];
    }

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'individual_id' => 'required|array',
            'individual_id.*' => 'required|numeric|exists:individuals,id',
        ];
    }

    /**
     * Get the individuals for the family.
     */
    public function individuals()
    {
        return $this->hasMany('App\Individual');
    }

    /**
     * Decides whether family renewal is already paid.
     *
     * @param int Renewal run id
     * @return bool
     */
    public function isFamilyRenewalAlreadyPaid($renewalRunId)
    {
        if (! optional($this)->individuals) {
            return false;
        }

        return IndividualRenewal::where('type_id', 2)
            ->whereHas('renewal', function ($query) use ($renewalRunId) {
                $query->where('renewal_run_id', $renewalRunId);
            })
            ->whereIn('individual_id', $this->individuals->pluck('id')->toArray())
            ->exists()
        ;
    }
}
