<?php

namespace App\Repositories;

use App\Models\Slot;
use App\Models\User;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;

class SlotsRepository extends Repository implements RepositoryInterface
{

    /**
     * @return string
     */
    public function model() {
        return Slot::class;
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
            return $this->model->whereHas('calendar', function($query) use ($user) {
                $query->whereHas('property', function($query) use ($user) {
                    $query->whereHas('companies', function($query) use ($user) {
                        $query->where('user_id', $user->getKey());
                    });
                });
            });
        }

        if($user->hasRole(User::ROLES[User::USER])) {
            return $this->model->whereHas('calendar', function($query) use ($user) {
                $query->whereHas('property', function($query) use ($user) {
                    $query->whereHas('memberships', function($query) use ($user) {
                        $query->where('user_id', $user->getKey());
                    });
                });
            });
        }

        return false;
    }
}
