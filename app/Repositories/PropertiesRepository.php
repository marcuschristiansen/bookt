<?php

namespace App\Repositories;

use App\Models\Property;
use App\Models\User;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;
use Illuminate\Database\Eloquent\Builder;

class PropertiesRepository extends Repository implements RepositoryInterface
{
    /**
     * @return string
     */
    public function model() {
        return Property::class;
    }

    /**
     * Fetches all the properties
     *
     * @return mixed
     */
    public function whereBelongsToAuthenticatedUser()
    {
        $ownedTeams = auth()->user()->ownedTeams->pluck('id')->toArray();
        return $this->model
            ->whereHas('users', function(Builder $query) {
                $query->where('id', auth()->user()->getKey());
            })
            ->orWhere('is_private', false)
            ->orWhereIn('team_id', $ownedTeams);
    }

    /**
     * @return mixed
     */
    public function whereHasPropertyMembership()
    {
        return $this->model->whereHas('users', function($query) {
            $query->where('user_id', auth()->user()->getKey());
        });
    }

    /**
     * @return mixed
     */
    public function isPublic(): mixed
    {
        return $this->model->where('is_private', 0);
    }
}
