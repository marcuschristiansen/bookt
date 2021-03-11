<?php
namespace App\Repositories\Criteria;

use App\Repositories\Contracts\RepositoryInterface as Repository;

class BelongsToTeam extends Criteria
{
    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        if(auth()->user()->currentTeam) {
            return $model->where('team_id', auth()->user()->currentTeam->id);
        }

        return false;
    }
}
