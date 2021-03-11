<?php

namespace App\Models;

use App\Traits\HasConditionalWith;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, Filterable, HasConditionalWith;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'slot_id',
        'date'
    ];

    /**
     * @var string[]
     */
    protected $withable = ['user', 'team', 'slot'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
//        'date' => 'datetime:Y-m-d'
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that owns the booking.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the slot that owns the booking.
     */
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
