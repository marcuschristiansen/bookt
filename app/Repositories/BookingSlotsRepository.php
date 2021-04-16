<?php

namespace App\Repositories;

use App\Models\BookingSlot;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;

class BookingSlotsRepository extends Repository implements RepositoryInterface
{
    /**
     * @return string
     */
    public function model() {
        return BookingSlot::class;
    }
}
