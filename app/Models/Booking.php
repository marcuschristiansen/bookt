<?php

namespace App\Models;

use App\Traits\HasConditionalWith;
use App\Traits\HasPasses;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    use HasPasses;
    use Filterable;
    use HasConditionalWith;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'property_id',
        'pass_id',
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
     * Get the property that owns the booking.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the pass that owns the booking.
     */
    public function pass()
    {
        return $this->belongsTo(Pass::class);
    }

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
