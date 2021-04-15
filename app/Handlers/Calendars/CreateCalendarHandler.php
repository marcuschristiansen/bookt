<?php

namespace App\Handlers\Calendars;

use App\Events\SlotCreated;
use App\Jobs\Calendars\CreateCalendar;
use App\Jobs\Properties\CreateProperty;
use App\Jobs\Slots\CreateSlot;
use App\Models\Property;
use App\Models\Slot;
use App\Repositories\CalendarsRepository;
use App\Repositories\PropertiesRepository;
use App\Repositories\SlotsRepository;

class CreateCalendarHandler
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
     * @param CreateCalendar $command
     * @return mixed
     */
    public function handle(CreateCalendar $command)
    {
        $request = $command->request;
        $propertyId = $command->propertyId;

        $fields = array_merge($request->validated(), [
            'property_id' => $propertyId,
        ]);

        return $this->calendar->create($fields);
    }
}
