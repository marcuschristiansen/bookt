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
     * Get the user for this membership
     */
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }

    /**
     * Get the team for this membership
     */
//    public function team()
//    {
//        return $this->belongsTo(Team::class);
//    }
}
