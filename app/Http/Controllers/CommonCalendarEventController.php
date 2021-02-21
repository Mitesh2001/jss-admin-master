<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use App\Discipline;
use Freshbitsweb\Laratables\Laratables;

class CommonCalendarEventController extends Controller
{
    protected $routePrefix;

    public function __construct()
    {
        $this->routePrefix = request()->is('captain/*') ? 'captain' : 'admin';
    }

    /**
     * Display a list of calendar events
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        $createUrl = $this->routePrefix . '.calendar-events.create';
        $datatableUrl = $this->routePrefix . '.calendar_events.datatables';
        $filterUrl = $this->routePrefix . '.calendar_events.filter';

        return view($this->routePrefix . '.calendar-events.index', compact('createUrl', 'datatableUrl', 'filterUrl'));
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(CalendarEvent::class);
    }

    /**
     * Display a add page of calender event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $disciplines = $this->getDisciplines();
        $storeUrl = $this->routePrefix . '.calendar-events.store';

        return view($this->routePrefix . '.calendar-events.add', compact('disciplines', 'storeUrl'));
    }

    /**
     * Store newly created calender event.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        // Security check
        $disciplineIds = auth()->user()->disciplines->pluck('id')->toArray();
        if (
            ! empty($disciplineIds) &&
            ! in_array(request('discipline_id'), $disciplineIds)
        ) {
            abort(404);
        }

        $validatedData = request()->validate(CalendarEvent::validationRules());

        CalendarEvent::create($this->prepareDetails($validatedData));

        return redirect()->route($this->routePrefix . '.calendar-events.index')->with([
            'type' => 'success',
            'message' => 'Event added successfully.'
        ]);
    }

    /**
     * Edit specified calendar event.
     *
     * @param \App\CalendarEvent $calendarEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(CalendarEvent $calendarEvent)
    {
        $disciplines = $this->getDisciplines();
        $updateUrl = $this->routePrefix . '.calendar-events.update';

        return view($this->routePrefix . '.calendar-events.edit', compact(['calendarEvent', 'disciplines', 'updateUrl']));
    }

    /**
     * Update specified calendar event.
     *
     * @param \App\CalendarEvent $calendarEvent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CalendarEvent $calendarEvent)
    {
        $validatedData = request()->validate(CalendarEvent::validationRules());

        $calendarEvent->update($this->prepareDetails($validatedData));

        return redirect()->route($this->routePrefix . '.calendar-events.index')->with([
            'type' => 'success',
            'message' => 'Event updated successfully.'
        ]);
    }

    /**
     * Deletes the calendar event
     *
     * @param \App\CalendarEvent $calendarEvent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();

        return redirect()->route($this->routePrefix . '.calendar-events.index')->with([
            'type' => 'success',
            'message' => 'Event deleted successfully.'
        ]);
    }

    /**
     * Returns the list of the disciplines
     *
     * @return \Illuminate\Support\Collection
     **/
    private function getDisciplines()
    {
        $disciplines = auth()->user()->disciplines;

        if (empty($disciplines->pluck('id')->toArray())) {
            return Discipline::getList();
        }

        return $disciplines;
    }

    /**
     * Finalize calendar event
     *
     * @param \App\CalendarEvent $calendarEvent
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function finalise(CalendarEvent $calendarEvent)
    {
        $calendarEvent->is_finalised = true;
        $calendarEvent->save();

        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Event finalised successfully.'
        ]);
    }

    /**
     * Unfinalize calendar event
     *
     * @param \App\CalendarEvent $calendarEvent
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function unFinalise(CalendarEvent $calendarEvent)
    {
        if (auth()->user()->type != 1) {
            abort(404);
        }

        $calendarEvent->is_finalised = false;
        $calendarEvent->save();

        return back()->with([
            'type' => 'success',
            'message' => 'Event unfinalised successfully.'
        ]);
    }

    /**
     * Sets the filter for records and displays the calendar events.
     *
     * @return Illuminate\Http\Response
     */
    public function filter()
    {
        session(['historical_finalised_events' => request('historical_finalised_events')]);

        return $this->index();
    }

    /**
     * Prepare calender event details
     *
     * @param array $validatedData
     * @return array $validatedData
     **/
    public function prepareDetails($validatedData)
    {
        if ($validatedData['is_attendance_tracked'] == 0) {
            $validatedData['score_type'] = 0;
        }

        if ($validatedData['is_public'] == 0) {
            $validatedData['start_time'] = '00:00';
            $validatedData['title'] = null;
            $validatedData['description'] = null;
        }

        return $validatedData;
    }
}
