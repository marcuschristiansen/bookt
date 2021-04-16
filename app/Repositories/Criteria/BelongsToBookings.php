<?php
namespace App\Repositories\Criteria;

use App\Models\Calendar;
use App\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Database\Eloquent\Collection;

class BelongsToBookings extends Criteria
{
    /**
     * @var Collection $bookings
     */
    public Collection $bookings;

    /**
     * ModelFilter constructor.
     *
     * @param  Collection $bookings
     */
    public function __construct(Collection $bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $bookingIds = $this->bookings->pluck('id')->toArray();
        return $model->whereIn('booking_id', $bookingIds);
    }
}
