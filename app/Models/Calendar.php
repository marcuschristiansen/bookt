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
        'team_id'
    ];

    /**
     * @var string[]
     */
    protected $withable = ['team'];

    /**
     * Get the team that owns the calendar.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the calendars slots
     *
     */
    public function slots()
    {
        return $this->hasMany(Slot::class)->orderBy('start_time');
    }
}
