<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pass extends Model
{
    use HasFactory;

    /**
     * Pass Types
     */
    public const TYPES = [
        'multi-slot' => 'Multi Slot',
        'full-day' => 'Full Day',
        'multi-day' => 'Multi Day'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'is_sequential',
        'select_count',
        'expiry_date',
        'cost'
    ];

    /**
     * Get the calendars that belongs to this property
     */
    public function calendars()
    {
        return $this->belongsToMany(Calendar::class);
    }

    /**
     * Check if the property that this calendar belongs to is public
     */
    public function propertyIsPublic()
    {
        return !$this->property->is_private;
    }

    /**
     * Check if the calendar is in the users team
     *
     * @param User $user
     */
    public function isInUsersTeams(User $user)
    {
        $properties = $user->allTeams()->pluck('properties')->flatten();
        return $properties->contains('id', $this->getKey());
    }

    /**
     * Check if the calendar belongs to a property that the user is a member of
     *
     * @param User $user
     */
    public function isInuserPropertyOfUser(User $user)
    {
        return $user->properties->contains('id', $this->property->getKey());
    }

    /**
     * @param Calendar $calendar
     */
    public function whereCalendar(Calendar $calendar)
    {
        return $this->whereHas('calendars', fn(Builder $query) =>
            $query->where('id', $calendar->getKey())
        );
    }
}
