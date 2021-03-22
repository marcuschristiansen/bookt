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
        'property_id',
        'slot_id',
        'date'
    ];

    /**
     * @var string[]
     */
    protected $withable = ['user', 'property', 'slot'];

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
     * Get the property that owns the booking.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the slot that owns the booking.
     */
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
