<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class RenewalRun extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'period', 'payment_due_date', 'start_date', 'expiry_date', 'status'
    ];

    /**
     * Get the emails of the renewal run.
     */
    public function emails()
    {
        return $this->hasMany('App\RenewalRunEmail');
    }

    /**
     * Get the entities of the renewal run.
     */
    public function entities()
    {
        return $this->hasMany('App\RenewalRunEntity');
    }

    /**
     * Returns the raw DB select query to fetch renewals count.
     */
    public static function renewalsSubmittedQuery()
    {
        return '(SELECT count(*) FROM `renewals`
            WHERE `renewals`.`renewal_run_id` = `renewal_runs`.`id`
            ) AS submitted_count'
        ;
    }

    /**
     * Returns the raw DB select query to fetch renewals count that are processed.
     */
    public static function renewalsProcessedQuery()
    {
        return '(SELECT count(*) FROM `renewals`
            WHERE `renewals`.`renewal_run_id` = `renewal_runs`.`id`
            ) AS processed_count'
        ;
    }

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'period' => 'required|string',
            'payment_due_date' => 'required|date',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date',
        ];
    }
}
