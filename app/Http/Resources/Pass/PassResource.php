<?php

namespace App\Http\Resources\Pass;

use App\Http\Resources\Calendar\CalendarCollection;
use App\Http\Resources\Slot\SlotResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PassResource extends JsonResource
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
            'type'       => 'passes',
            'id'         => (string)$this->getRouteKey(),
            'attributes' => [
                'name' => $this->start_time,
                'type' => $this->type,
                'is_sequential' => $this->is_sequential,
                'select_count' => $this->select_count,
                'expiry_date' => $this->expiry_date,
                'cost' => $this->cost,
                'calendars' => new CalendarCollection($this->calendars)
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
