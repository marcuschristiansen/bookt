<?php

namespace App\Handlers\Slots;

use App\Events\SlotCreated;
use App\Jobs\Calendars\CreateCalendar;
use App\Jobs\Properties\CreateProperty;
use App\Jobs\Slots\CreateSlot;
use App\Models\Property;
use App\Models\Slot;
use App\Repositories\CalendarsRepository;
use App\Repositories\PropertiesRepository;
use App\Repositories\SlotsRepository;

class CreateSlotHandler
{
    /**
     * @var SlotsRepository $slot
     */
    protected SlotsRepository $slot;

    public function __construct(SlotsRepository $slot)
    {
        $this->slot = $slot;
    }

    /**
     * @param CreateSlot $command
     * @return mixed
     */
    public function handle(CreateSlot $command)
    {
        $slot = $command->slot;
        $calendarId = $command->calendarId;

        $fields = array_merge($slot, [
            'calendar_id' => $calendarId,
        ]);

        return $this->slot->create($fields);
    }
}
