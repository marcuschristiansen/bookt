<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\User;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;

class BookingsRepository extends Repository implements RepositoryInterface
{

    /**
     * @return string
     */
    public function model() {
        return Booking::class;
    }

    /**
     * @return mixed
     */
    public function whereBelongsToAuthenticatedUser()
    {
        $user = auth()->user();

        if($user->hasRole(User::ROLES[User::SUPER_ADMIN])) {
            return $this->model->all();
        }

        if($user->hasRole(User::ROLES[User::COMPANY_ADMIN])) {
            return $this->model->whereIn('property_id', $user->properties->pluck('id')->toArray());
        }

        if($user->hasRole(User::ROLES[User::USER])) {
            return $this->model->where('user_id', $user->getKey());
        }

        return false;
    }
}
