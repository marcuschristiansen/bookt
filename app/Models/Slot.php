<?php

namespace App\Models;

use App\Traits\HasConditionalWith;
use App\Traits\HasPasses;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory;
    use HasPasses;
    use Filterable;
    use HasConditionalWith;
    use SoftDeletes;

    const DAYS = [
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];

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
        'max_bookings',
        'cost'
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
    protected $withable = ['calendar'];

    /**
     * Get the calendar that this slot belongs to
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * Get all the passes that own this slot
     */
    public function passes()
    {
//        return $this->belongsToMany(Pass::class);
    }

    /**
     * @param Calendar $calendar
     */
    public function whereCalendar(Calendar $calendar)
    {
        return $this->where('calendar_id', $calendar->getKey());
    }
}

