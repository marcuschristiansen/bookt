<?php

namespace App\Http\Resources\Booking;

use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Slot\SlotResource;
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
                'user' => new UserResource($this->user),
                'slot' => new SlotResource($this->slot),
                'date' => $this->date
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
