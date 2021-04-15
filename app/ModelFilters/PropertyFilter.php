<?php namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class PropertyFilter extends ModelFilter
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
     * @return PropertyFilter
     */
    public function team($value)
    {
        return $this->where('team_id', $value);
    }
}
