<?php

namespace App\Http\Controllers\Api;

use App\CalendarEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use Illuminate\Support\Facades\DB;

class CalendarEventsController extends Controller
{
    /**
     * Returns the list of events of previous year, current year, and next year.
     *
     * @return Json
     **/
    public function index()
    {
        $years = [
            now()->subYear()->format('Y'),
            now()->format('Y'),
            now()->addYear()->format('Y'),
        ];

        $calendarEvents = CalendarEvent::query()
            ->select('id', 'event_date', 'discipline_id', 'is_public', 'start_time', 'title', 'description')
            ->withoutGlobalScope('user')
            ->where('is_public', true)
            ->with('discipline:id,label')
            ->whereIn(DB::raw('YEAR(event_date)'), $years)
            ->get()
        ;

        return CalendarEventResource::collection($calendarEvents);
    }
}
