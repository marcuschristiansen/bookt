<?php namespace App\ModelFilters;

use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class SlotFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * Get the slots that correspond to the day
     *
     * @param $value
     * @return SlotFilter
     */
    public function date($value): SlotFilter
    {
        $day = Carbon::create($value)->format('N');

        return $this->where('day_id', $day);
    }

    /**Filter the slots by calendar
     *
     * @param string $value
     * @return SlotFilter
     */
    public function calendar(string $value): SlotFilter
    {
        return $this->where('calendar_id', $value);
    }
}
