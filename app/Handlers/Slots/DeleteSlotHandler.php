<?php

namespace App\Handlers\Slots;

use App\Jobs\Slots\DeleteSlot;
use App\Repositories\SlotsRepository;

class DeleteSlotHandler
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
     * @param DeleteSlot $command
     * @return mixed
     */
    public function handle(DeleteSlot $command)
    {
        $slot = $command->slot;

        return $this->slot->delete($slot->getKey());
    }
}
