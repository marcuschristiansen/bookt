<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('view properties');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function view(User $user, Property $property)
    {
        return $user->can('view properties') &&
            (
                $property->isPublic() ||
                $property->isPropertyMemberOf($user) ||
                $property->isOwnedByUser($user) ||
                $property->isInUsersTeams($user)
            );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create properties');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function update(User $user, Property $property)
    {
        return $user->can('edit properties') &&
            (
                $property->isOwnedByUser($user) ||
                $property->isInUsersTeams($user)
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function delete(User $user, Property $property)
    {
        return $user->can('delete properties') && $property->isOwnedByUser($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function restore(User $user, Property $property)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function forceDelete(User $user, Property $property)
    {
        //
    }
}
