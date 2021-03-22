<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pass extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all the slots in this pass
     */
    public function slots()
    {
        return $this->belongsToMany(Slot::class);
    }

    /**
     * Get all the calendars that this pass belongs to
     */
    public function calendars()
    {
        return $this->belongsToMany(Calendar::class);
    }

    /**
     * Get the team this pass belongs to
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
