<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Event extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type_id', 'comments', 'happened_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_happened_at'];

    /**
     * Get the formatted happened at for the event.
     *
     * @return bool
     */
    public function getFormattedHappenedAtAttribute()
    {
        return (new Carbon($this->attributes['happened_at']))->format('d-m-Y');
    }

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'type_id' => 'nullable|numeric|exists:event_types,id',
            'comments' => 'nullable|string',
            'happened_at' => 'nullable|date',
        ];
    }

    /**
     * Get the event type details.
     */
    public function type()
    {
        return $this->belongsTo('App\EventType');
    }
}
