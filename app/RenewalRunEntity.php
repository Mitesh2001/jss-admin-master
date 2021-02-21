<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** @mixin \Eloquent */
class RenewalRunEntity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['renewal_run_id', 'individual_id'];

    /**
     * Returns the raw DB select query to fetch renewals count.
     *
     * @param int Id of the renewal run
     * @return \Illuminate\Support\Collection
     */
    public static function getAllWith($renewalRunId)
    {
        $query = DB::table('renewal_run_entities')
            ->join('renewal_runs', function ($join) use ($renewalRunId) {
                $join->on('renewal_run_entities.renewal_run_id', '=', 'renewal_runs.id')
                    ->where('renewal_runs.id', $renewalRunId)
                ;
            })
        ;

        $query = static::addRenewalRunEmails($query);

        $query = static::addJoinQuery($query);

        $selectColumns = static::decideSelectColumns();

        return $query->get($selectColumns);
    }

    /**
     * Adds join query for renewal run emails.
     *
     * @param \Illuminate\Database\Query\Builder
     * @return \Illuminate\Database\Query\Builder
     */
    private static function addRenewalRunEmails($query)
    {
        $renewalRunEmails = DB::table('renewal_run_emails')
            ->select('renewal_run_id', DB::raw('MAX(sent_at) as renewal_run_email_sent_at'))
            ->whereNull('individual_id')
            ->groupBy('renewal_run_id')
        ;

        return $query->leftJoinSub($renewalRunEmails, 'renewal_run_emails', function ($join) {
            $join->on('renewal_runs.id', '=', 'renewal_run_emails.renewal_run_id');
        });
    }

    /**
     * Adds join query for individuals.
     *
     * @param \Illuminate\Database\Query\Builder
     * @return \Illuminate\Database\Query\Builder
     */
    private static function addJoinQuery($query)
    {
        $renewalsJoinMethod = 'leftJoin';
        if (session('renewals_run_details_filter') == 'submitted_only') {
            $renewalsJoinMethod = 'join';
        }

        $renewalRunEmails = DB::table('renewal_run_emails')
            ->select('renewal_run_id', 'individual_id', DB::raw('MAX(sent_at) as single_renewal_run_email_sent_at'))
            ->groupBy('renewal_run_id', 'individual_id')
        ;

        return $query->leftJoin('individuals', 'renewal_run_entities.individual_id', '=', 'individuals.id')
        ->leftJoinSub($renewalRunEmails, 'single_renewal_run_emails', function ($join) {
            $join->on('renewal_runs.id', '=', 'single_renewal_run_emails.renewal_run_id')
                ->on('individuals.id', '=', 'single_renewal_run_emails.individual_id')
            ;
        })
        ->leftJoin('individual_memberships', 'individuals.id', '=', 'individual_memberships.individual_id')
        ->$renewalsJoinMethod('renewals', function ($join) {
            $join->on('renewal_runs.id', '=', 'renewals.renewal_run_id')
                ->on('individuals.id', '=', 'renewals.individual_id')
            ;
        })
        ->leftJoin('individual_renewals', 'renewals.individual_renewal_id', '=', 'individual_renewals.id');
    }

    /**
     * Decides the columns to be selected as per the renewal run type.
     *
     * @return array
     */
    private static function decideSelectColumns()
    {
        return [
            'renewal_run_entities.*',
            'renewal_run_emails.renewal_run_email_sent_at',
            'single_renewal_run_emails.single_renewal_run_email_sent_at',
            'renewals.id as renewal_row_id',
            'renewals.receipt_id',
            'renewals.confirmation_emailed',
            'individuals.first_name',
            'individuals.surname',
            'individual_memberships.membership_number',
            'individual_memberships.type_id',
        ];
    }

    /**
     * Decides the status label of the renewal run entity.
     *
     * @param object Renewal run with relationships data
     * @return string
     */
    public static function decideStatus($renewalRunEntity)
    {
        if (! $renewalRunEntity->renewal_run_email_sent_at &&
            ! $renewalRunEntity->single_renewal_run_email_sent_at
        ) {
            return 'Not Sent'; // IMP: Label is used for a condition in blade view
        }

        if (! $renewalRunEntity->renewal_row_id) {
            return 'Not Submitted'; // IMP: Label is used for a condition in blade view
        }

        if (! $renewalRunEntity->confirmation_emailed) {
            return 'Waiting Processing';
        }

        return 'Processed/Completed';
    }

    /**
     * Get the individual of the renewal run entity
     **/
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }
}
