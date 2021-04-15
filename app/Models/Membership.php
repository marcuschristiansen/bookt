<?php

namespace App\Models;

use Laravel\Jetstream\Membership as JetstreamMembership;

class Membership extends JetstreamMembership
{
    /**
     * @var string $table
     */
    protected $table = 'team_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Get the team for this membership
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get all the users who are in this property
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
