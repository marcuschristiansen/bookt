<?php

namespace App\Http\Resources\Calendar;

use App\Http\Resources\Slot\SlotResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
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
            'type'       => 'calendars',
            'id'         => (string)$this->getRouteKey(),
            'attributes' => [
                'name' => $this->name
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
