<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class CalendarEvent extends Model
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
        "event_date", "score_type", "discipline_id", "is_finalised", "is_attendance_tracked", "is_public", "start_time", "title", "description"
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user', function (Builder $builder) {
            $disciplineIds = auth()->user()->disciplines->pluck('id')->toArray();

            if (! empty($disciplineIds)) {
                return $builder->whereIn('discipline_id', $disciplineIds);
            }
        });
    }

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'discipline_id' => 'required|numeric|exists:disciplines,id',
            'event_date' => 'required|date',
            'is_attendance_tracked' => 'required|boolean',
            'score_type' => 'required_if:is_attendance_tracked,1|in:1,2',
            'is_public' => 'required|boolean',
            'start_time' => 'required_if:is_public,1|nullable|string',
            'title' => 'required_if:is_public,1|nullable|string|max:25',
            'description' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the discipline of the calendar event.
     */
    public function discipline()
    {
        return $this->belongsTo('App\Discipline');
    }

    /**
     * Get the scores of the calendar event.
     */
    public function scores()
    {
        return $this->hasMany('App\CalendarEventScore');
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\CalendarEvent
     * @return string
     */
    public static function laratablesCustomAction($calendarEvent)
    {
        $routePrefix = request()->is('captain/*') ? 'captain' : 'admin';
        $viewUrl = $routePrefix . '.calendar-event.scores.index';
        $editUrl = $routePrefix . '.calendar-events.edit';
        $deleteUrl = $routePrefix . '.calendar-events.destroy';

        return view('common.calendar-events.action', compact('calendarEvent', 'viewUrl', 'editUrl', 'deleteUrl'))->render();
    }

    /**
     * Returns the status column html for datatables
     *
     * @param \App\CalendarEvent
     * @return string
     */
    public static function laratablesCustomStatus($calendarEvent)
    {
        return view('common.calendar-events.status', compact('calendarEvent'))->render();
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\CalendarEvent
     * @return string
     */
    public static function laratablesCustomAttendanceTracked($calendarEvent)
    {
        if (! $calendarEvent->is_attendance_tracked) {
            return 'No';
        }

        return $calendarEvent->score_type == 1 ? 'Yes - Point Scoring' : 'Yes - Deviation Scoring';
    }

    /**
     * Returns truncated name for the datatables.
     *
     * @param \App\CalendarEvent $event
     * @return string
     */
    public static function laratablesEventDate($event)
    {
        return Carbon::createFromFormat('Y-m-d', $event->event_date)->format('d-m-Y');
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['score_type', 'is_finalised', 'is_attendance_tracked'];
    }

    /**
     * Fetch only active users in the datatables.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        if (session('historical_finalised_events') == 'true') {
            return $query->where(function ($query) {
                $query->where('is_finalised', 1)
                    ->whereDate('event_date', '>=', now()->toDateString())
                ;
            })->orWhere(function ($query) {
                $query->where('is_finalised', 0)
                    ->whereDate('event_date', '<', now()->toDateString())
                ;
            })
                ->where('is_attendance_tracked', 1)
            ;
        }
        return $query;
    }

    /**
     * Returns the formatted date
     *
     * @return string event date
     **/
    public function getFormattedDate()
    {
        return Carbon::createFromFormat('Y-m-d', $this->event_date)->format('jS M Y');
    }
}
