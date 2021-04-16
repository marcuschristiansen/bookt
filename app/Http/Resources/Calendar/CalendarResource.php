<?php

namespace App\Http\Resources\Calendar;

use App\Http\Resources\Property\PropertyResource;
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
                'name' => $this->name,
                'property_id' => $this->property_id,
                'property' => new PropertyResource($this->whenLoaded('property')),
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
