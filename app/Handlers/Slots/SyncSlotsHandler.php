<?php

namespace App\Handlers\Slots;

use App\Jobs\Slots\SyncSlots;
use App\Repositories\SlotsRepository;

class SyncSlotsHandler
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
     * @param SyncSlots $command
     * @return mixed
     */
    public function handle(SyncSlots $command)
    {
        $slots = $command->slots;
        $calendar = $command->calendar;

        $updatedSlotIds = [];
        foreach ($slots as $slot) {
            foreach ($slot['items'] as $item) {
                if(array_key_exists('id', $item)) {
                    $this->slot->find($item['id'])->update($item);
                    $updatedSlotIds[] = $item['id'];
                } else {
                    $newSlot = $this->slot->create(array_merge($item, ['calendar_id' => $calendar->getKey(), 'day_id' => $slot['day_id']]));
                    $updatedSlotIds[] = $newSlot->getKey();
                }
            }
        }

        return $this->slot->where('calendar_id', $calendar->getKey())->whereNotIn('id', $updatedSlotIds)->delete();
    }
}
