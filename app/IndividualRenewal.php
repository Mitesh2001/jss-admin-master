<?php

namespace App;

use App\Traits\Addressable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class IndividualRenewal extends Model
{
    use Addressable, SoftDeletes, LogsActivity;

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
     * @param string renewal run start date
     * @return array
     **/
    public static function validationRules($renewalRunStartDate)
    {
        $validationRules = [
            'stripe_token' => 'required_if:payment_type,2|nullable|string',
            'gender_id' => 'required|numeric|exists:genders,id',
            'mobile_number' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'suburb_id' => 'nullable|numeric|exists:suburbs,id',
            'state_id' => 'nullable|numeric|exists:states,id',
            'post_code' => 'nullable|string|max:255',
            'ssaa_expiry' => 'required|date|date_format:Y-m-d|after_or_equal:' . $renewalRunStartDate,
            'type_id' => 'required|in:1,2,3',
            'renewal_applier_full_name' => 'required|string|max:255',
            'is_family_renewal_already_paid' => 'nullable|boolean',
            'payment_type' => 'required_without:is_family_renewal_already_paid|numeric|in:1,2',
            'amount' => 'required|numeric',
            'discount' => 'required|numeric',
        ];

        $adultOrPensionerRules = [
            'disciplines' => 'required_if:type_id,1,3|array',
            'disciplines.*' => 'required_if:type_id,1,3|numeric|exists:disciplines,id',
        ];

        $familyMemberRules = [
            'family_disciplines' => 'required_if:type_id,2|array',
            'family_disciplines.*' => 'required_if:type_id,2|numeric|exists:disciplines,id',
            'individuals' => 'required_if:type_id,2|array',
            'individuals[].*' => 'required_if:type_id,2|numeric|exists:individuals,id',
            'family_member' => 'required_if:type_id,2|nullable|array|min:1',
            'family_member.*' => 'required_if:type_id,2|nullable|numeric|exists:individuals,id',
        ];

        if (request('type_id') == 2) {
            return array_merge($validationRules, $familyMemberRules);
        }

        return array_merge($validationRules, $adultOrPensionerRules);
    }

    /**
    * The disciplines that belong to the individual renewal.
    */
    public function disciplines()
    {
        return $this->belongsToMany('App\Discipline')
            ->withTimestamps()
            ->withPivot('individual_id', 'is_lifetime_member', 'price')
        ;
    }

    /**
    * The family members that belong to the individual renewal.
    */
    public function familyMembers()
    {
        return $this->belongsToMany('App\Individual', 'individual_renewal_family_members', 'individual_renewal_id', 'family_member_id')
            ->withTimestamps()
            ->withPivot('is_pensioner', 'is_club_lifetime_member', 'is_committee_member')
        ;
    }

    /**
     * Get the individual that owns the renewal.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }

    /**
     * Get the renewal of the current renewal entry.
     */
    public function renewal()
    {
        return $this->hasOne('App\Renewal');
    }

    /**
     * Get the parent family member renewal.
     */
    public function parentRenewal()
    {
        return $this->belongsTo('App\IndividualRenewal');
    }

    /**
     * Returns the Custom name column for datatables.
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @return string
     */
    public static function laratablesCustomName($individualRenewal)
    {
        return view('admin.renewals.includes.index_name', compact('individualRenewal'))->render();
    }

    /**
     * Adds the condition for searching the name of the individual renewal submission in the query.
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
        return ['first_name', 'surname', 'payment_type', 'individual_id', 'parent_renewal_id'];
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\IndividualRenewal
     * @return string
     */
    public static function laratablesCustomAction($individualRenewal)
    {
        return view('admin.renewals.includes.index_action', compact('individualRenewal'))->render();
    }

    /**
     * Specify additional conditions for the query, if any.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        $query = $query->with([
            'renewal',
            'renewal.renewalRun',
            'renewal.receipt',
            'renewal.receipt.items',
            'renewal.receipt.payments',
            'individual',
            'parentRenewal',
            'parentRenewal.renewal',
        ]);

        $query = static::applyRenewalStatusFilter($query);

        $query = static::applyPaymentTypeFilter($query);

        $query = $query->whereHas('renewal', function ($query) {
            $query->whereHas('renewalRun', function ($query) {
                $query->where('status', true);
            });
        });

        return $query;
    }

    /**
     * Applies status filter conditions to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    private static function applyRenewalStatusFilter($query)
    {
        if (! session('individual_renewals_filter') || session('individual_renewals_filter') == 'incomplete_only') {
            return static::getStatusQuery($query, $status = 0);
        }

        if (session('individual_renewals_filter') == 'complete_only') {
            return static::getStatusQuery($query, $status = 1);
        }

        return $query;
    }

    /**
     * Make query conditions for the status filter
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param boolean $status
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public static function getStatusQuery($query, $status = 0)
    {
        return $query->where(function ($query) use ($status) {
            $query->where(function ($query) use ($status) {
                $query->whereHas('renewal', function ($query) use ($status) {
                    $query->where('confirmation_emailed', $status);
                })->whereIn('payment_type', [1, 2]);
            })->orWhere(function ($query) use ($status) {
                $query->whereHas('parentRenewal', function ($query) use ($status) {
                    $query->whereHas('renewal', function ($query) use ($status) {
                        $query->where('confirmation_emailed', $status);
                    });
                })->where('payment_type', 0);
            });
        });
    }

    /**
     * Applies payment type filter conditions to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    private static function applyPaymentTypeFilter($query)
    {
        if (session('individual_renewals_payment_type_filter') == 'online_only') {
            return static::getPaymentQuery($query, $paymentType = 2);
        }

        if (session('individual_renewals_payment_type_filter') == 'offline_only') {
            return static::getPaymentQuery($query, $paymentType = 1);
        }

        return $query;
    }

    /**
     * Make query conditions for the payment filter
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param int $paymentType
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public static function getPaymentQuery($query, $paymentType = 1)
    {
        return $query->where(function ($query) use ($paymentType) {
            $query->where(function ($query) use ($paymentType) {
                $query->where('payment_type', $paymentType);
            })->orWhere(function ($query) use ($paymentType) {
                $query->whereHas('parentRenewal', function ($query) use ($paymentType) {
                    $query->where('payment_type', $paymentType);
                });
            });
        });
    }

    /**
     * Returns the renewal of the family member head (1st member to submit the renewal).
     *
     * @param int $renewalRunId
     * @return \App\IndividualRenewal
     **/
    public function getRenewalOfFamilyMemberHead($renewalRunId)
    {
        $familyMemberIds = Individual::where('family_id', $this->individual->family_id)->get();

        return static::query()
            ->where('type_id', 2)
            ->whereIn('individual_id', $familyMemberIds->pluck('id'))
            ->whereIn('payment_type', [1, 2])
            ->whereHas('renewal', function ($query) use ($renewalRunId) {
                $query->where('renewal_run_id', $renewalRunId);
            })
            ->first()
        ;
    }
}
