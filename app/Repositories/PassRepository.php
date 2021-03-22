<?php

namespace App\Repositories;

use App\Models\Pass;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;

class PassRepository extends Repository implements RepositoryInterface
{
    /**
     * @return string
     */
    public function model() {
        return Pass::class;
    }
}
