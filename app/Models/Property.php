<?php

namespace App\Models;

use App\Traits\HasConditionalWith;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;
    use Filterable;
    use HasConditionalWith;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'name',
        'description',
        'address',
        'contact_number',
        'is_private'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_private' => 'boolean'
    ];

    /**
     * @var string[]
     */
    protected $withable = ['team', 'bookings'];

    /**
     * Get the team that this property belongs to
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the calendars that belongs to this property
     */
    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    /**
     * Get the users of this property
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get all the bookings for this property
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if the property is public
     *
     * @return bool
     */
    public function isPublic()
    {
        return !$this->is_private;
    }

    /**
     * Check if the property is part of the users property memberships
     *
     * @param User $user
     * @return mixed
     */
    public function isPropertyMemberOf(User $user)
    {
        return $user->properties->contains('id', $this->getKey());
    }

    /**
     * Check if the property is owned by this user
     *
     * @param User $user
     * @return bool
     */
    public function isOwnedByUser(User $user)
    {
        $properties = $user->ownedTeams->pluck('properties')->flatten();

        return $properties->contains('id', $this->getKey());
    }

    /**
     * Check if the property is in the users team
     *
     * @param User $user
     */
    public function isInUsersTeams(User $user)
    {
        $properties = $user->teams->pluck('properties')->flatten();

        return $properties->contains('id', $this->getKey());
    }
}
