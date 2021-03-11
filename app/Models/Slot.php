<?php

namespace App\Models;

use App\Traits\HasConditionalWith;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory, Filterable, HasConditionalWith;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'calendar_id',
        'day_id',
        'start_time',
        'end_time',
        'max_bookings'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'timestamp:H:i',
        'end_time' => 'timestamp:H:i',
    ];

    /**
     * @var string[]
     */
    protected $withable = ['calendar', 'bookings'];

    /**
     * Days of the week
     */
    public const DAYS = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    /**
     * Get the calendar that owns the slot.
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * Get all the bookings for this slot
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

