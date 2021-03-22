<?php namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CalendarFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * @param $value
     * @return CalendarFilter
     */
    public function property($value)
    {
        return $this->where('property_id', $value);
    }
}
