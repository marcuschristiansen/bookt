<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassRange extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'days',
        'dates',
        'pass_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'days' => 'array',
        'dates' => 'array',
    ];

    /**
     * The pass that this range belongs to
     */
    public function pass()
    {
        return $this->belongsTo(Pass::class);
    }
}
