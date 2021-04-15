<?php

namespace App\Traits;

use App\Models\Property;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Znck\Eloquent\Traits\BelongsToThrough;

trait HasProperties
{
    use BelongsToThrough;

    /**
     * @param Property $property
     * @return array
     */
    public function addToProperty(Property $property)
    {
        return $this->properties()->sync([$property->getKey()]);
    }

    /**
     * @param Collection $properties
     * @return array
     */
    public function addToProperties(Collection $properties)
    {
        return $this->properties()->sync($properties);
    }

    /**
     * Get the properties that this model is a member of
     */
    public function properties()
    {
        return $this->belongsToMany(Property::class)->as('property-membership');
    }

    /**
     * Get the properties that this user is a member of
     */
    public function ownedProperties()
    {
        return $this->hasManyThrough(Property::class, Team::class);
    }
}
