<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class IndividualMembership extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    public static $registrationTemplateId = 'jss-memberportal-registration';

    public static $passwordResetTemplateId = 'jss-memberportal-passwordreset';

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
    public static function validationRules()
    {
        return [
            'membership_number' => 'required|string|max:255',
            'join_date' => 'required|date',
            'membership_status' => 'required|boolean',
            'membership_type_id' => 'required|numeric|exists:membership_types,id',
            'expiry' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get the membership type of the individual membership.
     */
    public function type()
    {
        return $this->belongsTo('App\MembershipType', 'type_id', 'id');
    }

    /**
     * Get the individual of the membership.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }

    /**
     * Get the membership type label of the individual.
     */
    public function getPrice()
    {
        return optional($this->type)->price;
    }

    /**
     * Returns the id of the event type
     *
     * @param boolean membership status
     * @return int event type id
     **/
    public function getStatusEventTypeIdFor($status)
    {
        $eventTypeLabel = $status ? 'MEMBERSHIP MADE ACTIVE' : 'MEMBERSHIP MADE INACTIVE';
        return EventType::where('label', $eventTypeLabel)->first()->id;
    }

    /**
     * Returns the sparkpost registration template id
     *
     * @return string sparkpost registration template id
     **/
    public static function getRegisterTemplateId()
    {
        return static::$registrationTemplateId;
    }

    /**
     * Returns the sparkpost password reset template id
     *
     * @return string sparkpost password reset template id
     **/
    public static function getPasswordResetTemplateId()
    {
        return static::$passwordResetTemplateId;
    }
}
