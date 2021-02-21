<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Discipline extends StaticType
{
    use LogsActivity, SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function individualValidationRules()
    {
        return [
            'discipline_id' => 'sometimes|numeric|exists:disciplines,id',
            'is_lifetime_member' => 'required|boolean',
            'registered_at' => 'nullable|date',
            'approved_at' => 'nullable|date',
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
            'label' => 'required|string|max:255',
            'adult_price' => 'required|numeric',
            'family_price' => 'required|numeric',
            'pensioner_price' => 'required|numeric',
        ];
    }

    /**
     * The captain that belong to the discipline.
     */
    public function captains()
    {
        return $this->belongsToMany('App\User', 'captain_discipline', 'captain_id');
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\Discipline
     * @return string
     */
    public static function laratablesCustomAction($discipline)
    {
        return view('admin.disciplines.action', compact('discipline'))->render();
    }

    /**
     * Returns the url to the edit page of the discipline.
     *
     * @param \App\Discipline $discipline
     * @return array
     */
    public static function laratablesRowData($discipline)
    {
        return [
            'edit-url' => route('admin.disciplines.edit', ['discipline' => $discipline->id]),
        ];
    }

    /**
     * Returns formated adult price for the datatables.
     *
     * @param \App\Discipline $discipline
     * @return string
     */
    public static function laratablesAdultPrice($discipline)
    {
        return '$' . $discipline->adult_price;
    }

    /**
     * Returns formated family price for the datatables.
     *
     * @param \App\Discipline $discipline
     * @return string
     */
    public static function laratablesFamilyPrice($discipline)
    {
        return '$' . $discipline->family_price;
    }

    /**
     * Returns formated pensioner price for the datatables.
     *
     * @param \App\Discipline $discipline
     * @return string
     */
    public static function laratablesPensionerPrice($discipline)
    {
        return '$' . $discipline->pensioner_price;
    }
}
