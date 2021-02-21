<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Http\Controllers\Controller;
use App\Individual;

class IndividualEventController extends Controller
{
    /**
     * Store a newly created event in storage.
     *
     * @param  \App\Individual $individual
     * @return array
     */
    public function store(Individual $individual)
    {
        $validatedData = request()->validate(Event::validationRules());

        $event = $individual->events()->create($validatedData);

        return [
            'type' => 'success',
            'message' => 'Event added successfully.',
            'data' => $event->load('type'),
        ];
    }

    /**
     * Update specified event.
     *
     * @param int $individualId
     * @param \App\Event
     * @return array
     */
    public function update($individualId, Event $event)
    {
        $validatedData = request()->validate(Event::validationRules());

        $event->update($validatedData);

        return [
            'type' => 'success',
            'message' => 'Event updated successfully.',
            'data' => $event->load('type'),
        ];
    }

    /**
     * Deletes the event
     *
     * @param int individual id
     * @param \App\Event
     * @return void
     */
    public function destroy($individualId, Event $event)
    {
        $event->delete();

        return [
            'type' => 'success',
            'message' => 'Event deleted successfully.',
        ];
    }
}
