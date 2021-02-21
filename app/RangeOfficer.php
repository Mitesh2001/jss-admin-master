<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class RangeOfficer extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ "individual_id", "discipline_id", "added_date", ];

    /**
     * Returns the validation rules
     *
     * @return array
     **/
    public static function individualValidationRules()
    {
        return [
            'discipline_id' => 'required|exists:disciplines,id',
            'added_date' => 'required|date'
        ];
    }

    /**
     * Returns the validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'individual_id' => 'required|exists:individuals,id',
            'discipline_id' => 'required|exists:disciplines,id',
            'added_date' => 'required|date'
        ];
    }

    /**
     * Get the individual that owns the range officer.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }

    /**
     * Get the discipline that owns the range officer.
     */
    public function discipline()
    {
        return $this->belongsTo('App\Discipline');
    }

    /**
     * Returns formatted member name for the datatables.
     *
     * @param \App\RangeOfficer $officer
     * @return string
     */
    public static function laratablesIndividualFirstName($officer)
    {
        return $officer->individual->getName();
    }

    /**
     * Adds the condition for searching the name of the user in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchIndividualFirstName($query, $searchValue)
    {
        return $query->orWhere(function ($query) use ($searchValue) {
            $query->orWhereHas('individual', function ($query) use ($searchValue) {
                $query->where('first_name', 'like', '%'. $searchValue. '%')
                    ->orWhere('surname', 'like', '%'. $searchValue. '%')
                ;
            });
        });
    }

    /**
     * Fetch only active users in the datatables.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return $query->when(session('range_officer_discipline') != 'all', function ($query) {
            $query->where('discipline_id', session('range_officer_discipline'));
        });
    }

    /**
     * Returns the action column html for datatables.
     *
     * @param \App\RangeOfficer $officer
     * @return string
     */
    public static function laratablesCustomAction($officer)
    {
        return view('admin.range_officers.index_action', compact('officer'))->render();
    }
}
