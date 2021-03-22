<?php

namespace App\Models;

use App\Traits\HasConditionalWith;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory, Filterable, HasConditionalWith;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'property_id'
    ];

    /**
     * @var string[]
     */
    protected $withable = ['property'];

    /**
     * Get the property that owns the calendar.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get all the passes for this calendar
     *
     */
    public function passes()
    {
        return $this->belongsToMany(Pass::class);
    }
}
