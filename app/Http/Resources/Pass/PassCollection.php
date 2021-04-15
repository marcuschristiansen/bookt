<?php

namespace App\Http\Resources\Pass;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PassCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return PassResource::collection($this->collection);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array[]
     */
    public function with($request)
    {
        return [
            'links'    => [
//                'self' => route('slots.index'),
            ],
        ];
    }
}
