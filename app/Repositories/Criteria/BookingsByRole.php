<?php
namespace App\Repositories\Criteria;

use App\Models\Property;
use App\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

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

        $roles = Role::all();

        if($user->hasRole('team-admin')) {
            $properties = Property::where('team_id', auth()->user()->currentTeam->getKey())->pluck('id')->toArray();
            return $model->whereIn('property_id', $properties);
        }

        if($user->hasRole('user')) {
            return $model->where('user_id', $user->getKey());
        }

        return false;
    }
}
