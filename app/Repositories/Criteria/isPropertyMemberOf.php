<?php
namespace App\Repositories\Criteria;

use App\Repositories\Contracts\RepositoryInterface as Repository;

class isPropertyMemberOf extends Criteria
{
    /**
     * @var int $userId
     */
    public int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $repository->whereHasuserProperty($this->userId);
    }
}
