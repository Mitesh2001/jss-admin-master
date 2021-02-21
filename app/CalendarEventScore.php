<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class CalendarEventScore extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'individual_id', 'score', 'score_unit',
    ];

    /**
     * Get the individual details of the calender event score.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'individual_id' => 'required|numeric|exists:individuals,id',
            'score' => 'required|numeric|max:999999',
            'score_unit' => 'nullable|in:1,2',
        ];
    }

    /**
     * Returns the formatted score of the calendad event score
     *
     * @return string
     **/
    public function getFormattedScore($scoreType)
    {
        $scoreUnit = '';
        if ($scoreType == 2) {
            $scoreUnit = $this->score_unit == 1 ? 'mm' : '"';
        }

        return ($this->score + 0) . $scoreUnit;
    }
}
