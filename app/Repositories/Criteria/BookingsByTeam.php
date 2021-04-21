<?php
namespace App\Repositories\Criteria;

use App\Models\Property;
use App\Repositories\Contracts\RepositoryInterface as Repository;

class BookingsByTeam extends Criteria
{
    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $user = auth()->user();

        if($user->hasRole('team-admin')) {
            $properties = Property::where('team_id', auth()->user()->currentTeam->getKey())->pluck('id')->toArray();
            return $model->whereIn('property_id', $properties);
        }

        return false;
    }
}
