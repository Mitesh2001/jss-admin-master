<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class User extends Authenticatable
{
    use SoftDeletes, Notifiable, LogsActivity;

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
        'individual_id', 'username', 'password', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Profile update validation rules
     *
     * @return array
     **/
    public static function profileValidationRules()
    {
        return [
            'username' => 'required|string|max:255',
        ];
    }

    /**
     * Password update validation rules
     *
     * @return array
     **/
    public static function passwordValidationRules()
    {
        return [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Password update validation rules
     *
     * @param int $userId
     * @return array
     **/
    public static function validationRules($userId = null)
    {
        $uniqueRule = Rule::unique('users');

        if ($userId) {
            $uniqueRule->ignore($userId);
        }

        return [
            'individual_id' => 'required|numeric|exists:individuals,id',
            'discipline_ids' => 'required|array',
            'discipline_ids.*' => 'required|numeric|exists:disciplines,id',
            'username' => [
                'required',
                'string',
                $uniqueRule
            ],
            'password' => 'required|string|min:8',
        ];
    }

    /**
     * Get the individual of the user.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }

    /**
     * The discipline that belong to the user.
     */
    public function disciplines()
    {
        return $this->belongsToMany('App\Discipline', 'captain_discipline', 'captain_id');
    }

    /**
     * Returns the individual name column html for datatables
     *
     * @param \App\User $user
     * @return string
     */
    public static function laratablesCustomIndividualName($user)
    {
        return optional(optional($user)->individual)->getName();
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['individual_id', 'type'];
    }

    /**
     * Fetch only captain users in the datatables.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return $query->with('individual')->where('type', 2);
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\User $user
     * @return string
     */
    public static function laratablesCustomAction($user)
    {
        return view('admin.users.action', compact('user'))->render();
    }
}
