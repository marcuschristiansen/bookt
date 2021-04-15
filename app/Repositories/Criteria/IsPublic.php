<?php
namespace App\Repositories\Criteria;

use App\Repositories\Contracts\RepositoryInterface as Repository;

class IsPublic extends Criteria
{
    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $repository->isPublic();
    }
}
