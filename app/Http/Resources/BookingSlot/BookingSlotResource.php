<?php

namespace App\Http\Resources\BookingSlot;

use App\Http\Resources\Booking\BookingResource;
use App\Http\Resources\Slot\SlotResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingSlotResource extends JsonResource
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
            'type'       => 'bookingSlots',
            'id'         => (string)$this->getRouteKey(),
            'attributes' => [
                'booking_id' => (int)$this->booking_id,
                'booking' => new BookingResource($this->whenLoaded('booking')),
                'slot_id' => (int)$this->slot_id,
                'slot' => new SlotResource($this->whenLoaded('slot')),
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
