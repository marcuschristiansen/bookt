<?php

namespace App\Repositories;

use App\Models\UserProperty;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;

class UserPropertyRepository extends Repository implements RepositoryInterface
{
    /**
     * @return string
     */
    public function model() {
        return UserProperty::class;
    }
}
