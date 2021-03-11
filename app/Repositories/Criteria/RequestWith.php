<?php
namespace App\Repositories\Criteria;

use App\Repositories\Criteria\Criteria;
use App\Repositories\Contracts\RepositoryInterface as Repository;

class RequestWith extends Criteria
{

    /**
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $model->withRelations();

        return $model;
    }
}