<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Firearm extends Model
{
    use LogsActivity, SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "firearm_type_id", "make", "model", "calibre", "serial", "discipline_id", "support_granted_at", "support_removed_at", "mark_as_disposed_at", "support_reason", "disposed_reason"
    ];

    /**
     * Returns the validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            "individual_ids" => 'required|array',
            "individual_ids.*" => 'required|exists:individuals,id',
            "firearm_type_id" => 'required|exists:firearm_types,id',
            "serial" => 'required|alpha_num',
            "make" => 'required|string',
            "model" => 'required|string',
            "calibre" => 'required|string',
            "discipline_id" => 'required|exists:disciplines,id',
            "support_granted_at" => 'required|date',
        ];
    }

    /**
     * Get the individual that owns the firearm.
     */
    public function individuals()
    {
        return $this->belongsToMany('App\Individual');
    }

    /**
     * Get the discipline that owns the firearm.
     */
    public function discipline()
    {
        return $this->belongsTo('App\Discipline');
    }

    public function type()
    {
        return $this->belongsTo('App\FirearmType', 'firearm_type_id');
    }

    /**
     * Load action type.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        if ($individualId = request('individual_id')) {
            return $query->whereNull('support_removed_at')
                ->whereNull('mark_as_disposed_at')
                ->whereHas('individuals', function ($query) use ($individualId) {
                    $query->where('id', $individualId);
                })
            ;
        }

        $disciplineId = session('firearm_discipline_type');
        $status = session('firearm_status');
        $memberStatus = session('firearm_membership_status');
        $membershipNumber = session('firearm_membership_number');

        return $query->with('individuals')->when($disciplineId, function ($query) use ($disciplineId) {
            $query->where('discipline_id', $disciplineId);
        })->when($status == 1, function ($query) {
            $query->whereNull('support_removed_at')
                ->whereNull('mark_as_disposed_at')
            ;
        })->when($status == 2, function ($query) {
            $query->whereNotNull('support_removed_at')
                ->orWhereNotNull('mark_as_disposed_at')
            ;
        })->when($memberStatus == 1, function ($query) {
            $query->whereHas('individuals', function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->where('status', true);
                });
            });
        })->when($memberStatus == 2, function ($query) {
            $query->whereHas('individuals', function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->where('status', false);
                });
            });
        })->when($membershipNumber, function ($query) use ($membershipNumber) {
            $query->whereHas('individuals', function ($query) use ($membershipNumber) {
                $query->whereHas('membership', function ($query) use ($membershipNumber) {
                    $query->where('membership_number', 'LIKE', '%'.$membershipNumber.'%');
                });
            });
        });
    }

    /**
     * Returns formatted firearm for the datatables.
     *
     * @param \App\Firearm $firearm
     * @return string
     */
    public static function laratablesMake($firearm)
    {
        return $firearm->make.' '.$firearm->model;
    }

    /**
     * Adds the condition for searching the make and model of the fire arm in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchMake($query, $searchValue)
    {
        return $query->orWhere('make', 'like', '%'. $searchValue. '%')
            ->orWhere('model', 'like', '%'. $searchValue. '%')
        ;
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['model', 'calibre', 'firearm_type_id', 'support_removed_at', 'mark_as_disposed_at', 'support_reason', 'disposed_reason'];
    }

    /**
     * Returns the status column html for datatables.
     *
     * @param \App\Firearm $firearm
     * @return string
     */
    public static function laratablesCustomStatus($firearm)
    {
        if ($firearm->support_removed_at) {
            return 'Inactive <i class="fa fa-info-circle"
                title="Removed '.$firearm->support_removed_at.' - '.$firearm->support_reason.
            '"></i>';
        }

        if ($firearm->mark_as_disposed_at) {
            return 'Inactive <i class="fa fa-info-circle"
                title="Disposed '.$firearm->mark_as_disposed_at.' - '.$firearm->disposed_reason.
            '"></i>';
        }

        return 'Supported';
    }

    public static function laratablesCustomIndividuals(Firearm $firearm)
    {
        return $firearm->individuals->map(function ($individual) {
            return [
                'name' => $individual->getName(),
            ];
        })->implode('name', ', ');
    }

    public static function laratablesSearchIndividuals($query, $searchValue)
    {
        return $query->orWhereHas('individuals', function ($query) use ($searchValue) {
            return $query->where('first_name', 'like', '%'. $searchValue. '%')
                ->orWhere('surname', 'like', '%'. $searchValue. '%')
            ;
        });
    }

    /**
     * Returns truncated name for the datatables.
     *
     * @param \App\User
     * @return string
     */
    public static function laratablesSupportGrantedAt($firearm)
    {
        return Carbon::createFromFormat('Y-m-d', $firearm->support_granted_at)->format('d-m-Y');
    }

    /**
     * Returns the action column html for datatables.
     *
     * @param \App\Firearm $firearm
     * @return string
     */
    public static function laratablesCustomAction($firearm)
    {
        return view('admin.firearms.index_action', compact('firearm'))->render();
    }
}
