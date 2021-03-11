<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Company\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'type'       => 'users',
            'id'         => (string)$this->getRouteKey(),
            'attributes' => [
                'name'  => $this->name,
                'email' => $this->email,
            ],
            'links'      => [
//                'self' => route('courses.show', ['user' => $this->getRouteKey()]),
            ],
        ];
    }
}
