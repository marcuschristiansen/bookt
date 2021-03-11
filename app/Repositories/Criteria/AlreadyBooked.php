<?php
namespace App\Repositories\Criteria;

use App\Repositories\Criteria\Criteria;
use App\Repositories\Contracts\RepositoryInterface as Repository;

class AlreadyBooked extends Criteria
{
    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->whereDoesntHave('bookings', function($query) {
            $query->where('user_id', '!=', auth()->user());
        });
    }
}
