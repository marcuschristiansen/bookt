<?php

namespace App\Http\Resources\Booking;

use App\Http\Resources\Pass\PassResource;
use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'type'       => 'bookings',
            'id'         => (string)$this->getRouteKey(),
            'attributes' => [
                'user_id' => (int)$this->user_id,
                'user' => new UserResource($this->user),
                'pass_id' => (int)$this->pass_id,
                'pass' => new PassResource($this->pass),
                'property_id' => (int)$this->property_id,
                'property' => new PropertyResource($this->property),
                'date' => $this->date
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
