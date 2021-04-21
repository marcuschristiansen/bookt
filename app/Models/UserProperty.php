<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserProperty extends Pivot
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'property_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id',
        'user_id'
    ];

    /**
     * Get the property that owns the membership.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user that owns the membership.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the model belongs to user
     *
     * @param User $user
     */
    public function belongsToUser(User $user)
    {
        return $this->user() === $user;
    }
}
