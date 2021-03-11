<?php
namespace App\Repositories\Criteria;

use App\Models\Calendar;
use App\Repositories\Criteria\Criteria;
use App\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Support\Facades\Log;

class BelongsToCalendar extends Criteria
{
    /**
     * @var Calendar $calendar
     */
    public $calendar;

    /**
     * ModelFilter constructor.
     * @param Calendar $calendar
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $model->where('calendar_id', $this->calendar->getKey());

        return $model;
    }
}
