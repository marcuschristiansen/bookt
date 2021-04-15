<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'type'       => 'teams',
            'id'         => (string)$this->getRouteKey(),
            'attributes' => [
                'user' => new UserResource($this->whenLoaded('user')),
                'name' => $this->name,
                'personal_team' => $this->personal_team
            ],
            'links'      => [
//                'self' => route('companies.show', ['company' => $this->getRouteKey()]),
            ],
        ];
    }
}
