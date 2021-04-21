<?php

namespace App\Http\Resources\Property;

use App\Http\Resources\Calendar\CalendarCollection;
use App\Http\Resources\Calendar\CalendarResource;
use App\Http\Resources\Slot\SlotResource;
use App\Http\Resources\Team\TeamResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'type'       => 'properties',
            'id'         => $this->getRouteKey(),
            'attributes' => [
                'team_id' => $this->team_id,
                'team' => new TeamResource($this->whenLoaded('team')),
                'calendars' => CalendarResource::collection($this->whenLoaded('calendars')),
                'users' => UserResource::collection($this->whenLoaded('users')),
                'name' => $this->name,
                'joining_code' => $this->joining_code,
                'description' => $this->description,
                'address' => $this->address,
                'contact_number' => $this->contact_number,
                'is_private' => $this->is_private,

            ],
            'links'      => [
//                'self' => route('properties.show', ['property' => $this->getRouteKey()]),
            ],
        ];
    }
}
