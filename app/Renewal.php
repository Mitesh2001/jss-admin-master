<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Renewal extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'receipt_id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Validation rules
     *
     * @return array validation rules
     **/
    public static function validationRules()
    {
        return [
            'record_payment' => 'required|boolean',
            'email_confirmation' => 'required|boolean',
            'is_print_card' => 'required|boolean'
        ];
    }

    /**
     * Returns whether an individual is requested a renewal already.
     *
     * @param int Id of the individual
     * @param int Id of the Renewal run
     * @return bool
     */
    public static function isRequestedAlready($individualId, $renewalRunId)
    {
        return static::where('individual_id', $individualId)
            ->where('renewal_run_id', $renewalRunId)
            ->exists()
        ;
    }

    /**
     * Get the corresponding individual renewal.
     */
    public function iRenewal()
    {
        return $this->belongsTo('App\IndividualRenewal', 'individual_renewal_id', 'id');
    }

    /**
     * Get the individual that owns the renewal.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }

    /**
     * Get the receipt associated with the renewal.
     */
    public function receipt()
    {
        return $this->belongsTo('App\Receipt');
    }

    /**
     * Get the renewal run of the renewal.
     */
    public function renewalRun()
    {
        return $this->belongsTo('App\RenewalRun');
    }

    /**
     * Returns the outstanding amount of the renewal.
     *
     * @return float
     */
    public function getOutstandingAmount()
    {
        return optional($this->receipt)->getOutstandingAmount();
    }
}
