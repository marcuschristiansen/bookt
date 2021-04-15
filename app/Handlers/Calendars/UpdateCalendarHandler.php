<?php

namespace App\Handlers\Calendars;

use App\Jobs\Calendars\UpdateCalendar;
use App\Jobs\Properties\UpdateProperty;
use App\Repositories\CalendarsRepository;
use App\Repositories\PropertiesRepository;

class UpdateCalendarHandler
{
    /**
     * @var CalendarsRepository $calendar
     */
    protected CalendarsRepository $calendar;

    public function __construct(CalendarsRepository $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param UpdateCalendar $command
     * @return mixed
     */
    public function handle(UpdateCalendar $command)
    {
        $request = $command->request;
        $calendar = $command->calendar;

        $this->calendar->updateRich($request->validated(), $calendar->getKey());

        return $this->calendar->find($calendar->getKey());
    }
}
