<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\UserProperty;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param int $userId
     * @return bool
     */
    public function viewAny(User $user, int $userId)
    {
        return $user->can('view user properties') && $user->getKey() === $userId;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserProperty  $userProperty
     * @return mixed
     */
    public function view(User $user, UserProperty $userProperty)
    {
        //
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
     * @param  \App\Models\UserProperty  $userProperty
     * @return mixed
     */
    public function update(User $user, UserProperty $userProperty)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserProperty  $userProperty
     * @return mixed
     */
    public function delete(User $user, UserProperty $userProperty)
    {
        return $user->can('delete user properties') && $userProperty->belongsToUser($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserProperty  $userProperty
     * @return mixed
     */
    public function restore(User $user, UserProperty $userProperty)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserProperty  $userProperty
     * @return mixed
     */
    public function forceDelete(User $user, UserProperty $userProperty)
    {
        //
    }
}
