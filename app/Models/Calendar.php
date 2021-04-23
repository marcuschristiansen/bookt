<?php

namespace App\Models;

use App\Traits\HasConditionalWith;
use App\Traits\HasSlots;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
    use HasFactory;
    use HasSlots;
    use Filterable;
    use HasConditionalWith;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'property_id'
    ];

    /**
     * @var string[]
     */
    protected $withable = ['property'];

    /**
     * Get the property that owns the booking.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get all the passes for this calendar
     *
     */
    public function passes()
    {
        return $this->belongsToMany(Pass::class);
    }

    /**
     * Get all the slots for this calendar
     */
    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    /**
     * @return array
     */
    public function slotsGroupedByDay(): array
    {
        $slots = [];

        foreach ($this->slots as $slot) {
            $slots[$slot['day_id']]['day_id'] = $slot['day_id'];
            $slots[$slot['day_id']]['day'] = Slot::DAYS[$slot['day_id']];
            $slots[$slot['day_id']]['items'][] = $slot;
        }

        return $slots;
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
    public function isInUserPropertyOfUser(User $user)
    {
        return $user->properties->contains('id', $this->property->getKey());
    }

    /**
     * @param Property $property
     */
    public function whereProperty(Property $property)
    {
        return $this->where('property_id', $property->getKey());
    }
}
