<?php

namespace App\Handlers\Calendars;

use App\Jobs\Calendars\DeleteCalendar;
use App\Repositories\CalendarsRepository;

class DeleteCalendarHandler
{
    /**
     * @var CalendarsRepository $calenar
     */
    protected CalendarsRepository $calendar;

    public function __construct(CalendarsRepository $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param DeleteCalendar $command
     * @return mixed
     */
    public function handle(DeleteCalendar $command)
    {
        $calendar = $command->calendar;

        return $this->calendar->delete($calendar->getKey());
    }
}
