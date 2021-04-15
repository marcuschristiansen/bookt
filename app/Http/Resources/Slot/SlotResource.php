<?php

namespace App\Http\Resources\Slot;

use App\Http\Resources\Calendar\CalendarResource;
use App\Http\Resources\Pass\PassResource;
use App\Http\Resources\Team\TeamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends JsonResource
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
            'type'       => 'slots',
            'id'         => (string)$this->getRouteKey(),
            'attributes' => [
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'max_bookings' => (int)$this->max_bookings,
                'calendar_id' => $this->calendar_id,
                'calendar' => new CalendarResource($this->calendar),
                'day_id' => $this->day_id,
                'cost' => $this->cost
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
