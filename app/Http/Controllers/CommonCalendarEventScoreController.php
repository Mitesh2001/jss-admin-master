<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use App\CalendarEventScore;
use App\Individual;

class CommonCalendarEventScoreController extends Controller
{
    public function __construct()
    {
        $this->routePrefix = request()->is('captain/*') ? 'captain' : 'admin';
    }

    /**
     * Display a list of calendar events
     *
     * @param \App\CalendarEvent $calendarEvent
     * @return \Illuminate\Http\Response
     **/
    public function index(CalendarEvent $calendarEvent)
    {
        if (! $calendarEvent->is_attendance_tracked) {
            abort(404);
        }

        $individuals = Individual::query()
            ->with(['membership:id,individual_id,membership_number'])
            ->select('id', 'first_name', 'middle_name', 'surname')
            ->whereHas('membership')
            ->orderBy('first_name')
            ->get()
        ;

        $calendarEvent->load(['scores', 'scores.individual', 'scores.individual.membership:id,individual_id,membership_number']);

        $storeUrl = $this->routePrefix . '.calendar-event.scores.store';
        $deleteUrl = $this->routePrefix . '.calendar-event.scores.destroy';
        $finaliseUrl = $this->routePrefix . '.calendar_events.finalise';
        $unfinaliseUrl = auth()->user()->type == 1 ? $this->routePrefix . '.calendar_events.unfinalise' : '';

        return view(
            $this->routePrefix . '.calendar-events.scores.index',
            compact('individuals', 'calendarEvent', 'storeUrl', 'deleteUrl', 'finaliseUrl', 'unfinaliseUrl')
        );
    }

    /**
     * Store newly created calender event.
     *
     * @param \App\CalendarEvent $calendarEvent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CalendarEvent $calendarEvent)
    {
        if (! $calendarEvent->is_attendance_tracked) {
            abort(404);
        }

        $validatedData = request()->validate(CalendarEventScore::validationRules());

        $calendarEvent->scores()->create($validatedData);

        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Score added successfully.'
        ]);
    }

    /**
     * Deletes the calendar event
     *
     * @param CalendarEvent $calendarEvent
     * @param int $calendarEventScoreId
     * @return array
     */
    public function destroy(CalendarEvent $calendarEvent, $calendarEventScoreId)
    {
        if (! $calendarEvent->is_attendance_tracked) {
            abort(404);
        }

        CalendarEventScore::find($calendarEventScoreId)->delete();

        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Score deleted successfully.'
        ]);
    }
}
