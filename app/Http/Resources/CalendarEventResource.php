<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'event_date' => $this->event_date,
            'event_time' => $this->start_time,
            'discipline_id' => $this->discipline->id,
            'discipline_name' => $this->discipline->label,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
