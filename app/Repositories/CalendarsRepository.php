<?php

namespace App\Repositories;

use App\Models\Calendar;
use App\Models\User;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;

class CalendarsRepository extends Repository implements RepositoryInterface
{

    /**
     * @return string
     */
    public function model() {
        return Calendar::class;
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
            return $this->model->whereHas('property', function($query) use ($user) {
                $query->whereHas('company', function($query) use ($user) {
                    $query->where('user_id', $user->getKey());
                });
            });
        }

        if($user->hasRole(User::ROLES[User::USER])) {
            return $this->model->whereHas('property', function($query) use ($user) {
                $query->whereHas('memberships', function($query) use ($user) {
                    $query->where('user_id', $user->getKey());
                });
            });
        }

        return false;
    }
}
