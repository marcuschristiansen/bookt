<?php namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class BookingFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

//    public function search($value)
//    {
//        return $this->whereRaw("name LIKE '%$value%'");
//    }

    /**
     * @param $value
     * @return BookingFilter
     */
    public function user($value)
    {
        return $this->where('user_id', $value);
    }

    /**
     * @param string $value
     * @return BookingFilter
     */
    public function property($value)
    {
        return $this->where('property_id', $value);
    }

    /**
     * Filter by date
     *
     * @param string $value
     * @return BookingFilter
     */
    public function date(string $value): BookingFilter
    {
        return $this->where('date', '=', $value);
    }
}
