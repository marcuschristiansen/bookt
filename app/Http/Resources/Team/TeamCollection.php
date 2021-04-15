<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TeamCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return TeamResource::collection($this->collection);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array[]
     */
    public function with($request)
    {
        return [
            'links'    => [
//                'self' => route('bookings.index'),
            ],
        ];
    }
}
