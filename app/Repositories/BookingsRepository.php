<?php

namespace App\Repositories;

use App\Models\Booking;
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

        return $this->model->where('user_id', $user->getKey());
    }
}
