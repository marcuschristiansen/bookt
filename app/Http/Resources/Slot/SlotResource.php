<?php

namespace App\Http\Resources\Slot;

use App\Http\Resources\Calendar\CalendarResource;
use App\Http\Resources\User\UserResource;
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
                'calendar' => new CalendarResource($this->calendar),
                'day' => $this->day_id,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'max_bookings' => $this->max_bookings
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
