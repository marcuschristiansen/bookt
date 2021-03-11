<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'owns_current_team',
        'next_booking'
    ];

    /**
     * Get the bookings of the user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the next booking
     */
    public function getNextBookingAttribute()
    {
        return $this->bookings->sortBy('date')->first();
    }

    /**
     *
     * @return bool
     */
    public function getOwnsCurrentTeamAttribute()
    {
        return $this->ownsTeam($this->currentTeam);
    }

    /**
     * Get all of the teams the user owns or belongs to excluding their personal team
     *
     * @return Collection
     */
    public function allTeams()
    {
        if($this->hasPermissionTo('edit teams')) {
            return $this->ownedTeams->merge($this->teams)->sortBy('name');
        }

        return $this->teams->sortBy('name');
    }
}