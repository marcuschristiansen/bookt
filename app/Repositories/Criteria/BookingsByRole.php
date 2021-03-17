<?php
namespace App\Repositories\Criteria;

use App\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Support\Facades\Log;

class BookingsByRole extends Criteria
{
    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $user = auth()->user();

        if($user->ownsTeam($user->currentTeam)) {
            return $model->where('team_id', $user->currentTeam->getKey());
        }

        if($user->hasRole('user')) {
            return $model->where('user_id', $user->getKey());
        }

        return false;
    }
}
