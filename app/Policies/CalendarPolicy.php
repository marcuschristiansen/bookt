<?php

namespace App\Policies;

use App\Models\Calendar;
use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
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
        return $user->can('view calendars');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar  $calendar
     * @return mixed
     */
    public function view(User $user, Calendar $calendar)
    {
        return $user->can('view calendars') &&
            (
                $calendar->isInUsersTeams($user) ||
                $calendar->propertyIsPublic() ||
                $calendar->isInPropertyMembershipOfUser($user)
            );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Property $property
     * @return mixed
     */
    public function create(User $user, Property $property)
    {
        return $user->can('create calendars') &&
            (
                $property->isInUsersTeams($user) ||
                $property->isOwnedByUser($user)
            );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Calendar $calendar
     * @return mixed
     */
    public function update(User $user, Calendar $calendar)
    {
        return $user->can('edit calendars') &&
            (
                $calendar->property->isOwnedByUser($user) ||
                $calendar->property->isInUsersTeams($user)
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Calendar $calendar
     * @return mixed
     */
    public function delete(User $user, Calendar $calendar)
    {
        return $user->can('delete calendars') &&
            (
                $calendar->property->isOwnedByUser($user) ||
                $calendar->property->isInUsersTeams($user)
            );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar  $calendar
     * @return mixed
     */
    public function restore(User $user, Calendar $calendar)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar  $calendar
     * @return mixed
     */
    public function forceDelete(User $user, Calendar $calendar)
    {
        //
    }
}
