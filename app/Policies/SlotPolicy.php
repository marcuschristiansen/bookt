<?php

namespace App\Policies;

use App\Models\Calendar;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlotPolicy
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
        return $user->can('view slots');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slot  $slot
     * @return mixed
     */
    public function view(User $user, Slot $slot)
    {
        return $user->can('view slots') &&
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
    public function create(User $user, Calendar $calendar)
    {
        return $user->can('create slots') &&
            (
                $calendar->property->isInUsersTeams($user) ||
                $calendar->property->isOwnedByUser($user)
            );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slot  $slot
     * @return mixed
     */
    public function update(User $user, Slot $slot)
    {
        return $user->can('edit slots') &&
            (
                $slot->calendar->property->isOwnedByUser($user) ||
                $slot->calendar->property->isInUsersTeams($user)
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slot  $slot
     * @return mixed
     */
    public function delete(User $user, Slot $slot)
    {
        return $user->can('delete slots') &&
            (
                $slot->calendar->property->isOwnedByUser($user) ||
                $slot->calendar->property->isInUsersTeams($user)
            );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slot  $slot
     * @return mixed
     */
    public function restore(User $user, Slot $slot)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slot  $slot
     * @return mixed
     */
    public function forceDelete(User $user, Slot $slot)
    {
        //
    }
}
