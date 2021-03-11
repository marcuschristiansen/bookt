<?php
namespace App\Repositories\Criteria;

use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Contracts\RepositoryInterface as Repository;

class ModelFilter extends Criteria
{

    /**
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $model->filter(request()->all());

        return $model;
    }
}
