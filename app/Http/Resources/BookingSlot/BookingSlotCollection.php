<?php

namespace App\Http\Resources\BookingSlot;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingSlotCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return BookingSlotResource::collection($this->collection);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array[]
     */
    public function with($request)
    {
        return [
            'links'    => [
//                'self' => route('bookingSlots.index'),
            ],
        ];
    }
}
