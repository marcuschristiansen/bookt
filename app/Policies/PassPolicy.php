<?php

namespace App\Policies;

use App\Models\Pass;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PassPolicy
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
        return $user->can('view passes');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pass  $pass
     * @return mixed
     */
    public function view(User $user, Pass $pass)
    {
        return $user->can('view passes') &&
            (
                $slot->calendar->isInUsersTeams($user) ||
                $slot->calendar->propertyIsPublic() ||
                $slot->calendar->isInuserPropertyOfUser($user)
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
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pass  $pass
     * @return mixed
     */
    public function update(User $user, Pass $pass)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pass  $pass
     * @return mixed
     */
    public function delete(User $user, Pass $pass)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pass  $pass
     * @return mixed
     */
    public function restore(User $user, Pass $pass)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pass  $pass
     * @return mixed
     */
    public function forceDelete(User $user, Pass $pass)
    {
        //
    }
}
