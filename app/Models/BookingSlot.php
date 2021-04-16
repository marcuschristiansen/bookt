<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingSlot extends Pivot
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slot_id',
        'booking_id',
    ];

    /**
     * Get the booking for this model
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the slot for this model
     */
    public function slot()
    {
        return $this->belongsTo(Slot::class)->orderBy('start_time');
    }
}
