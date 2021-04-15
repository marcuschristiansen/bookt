<?php

namespace App\Handlers\Slots;

use App\Jobs\Slots\UpdateSlot;
use App\Repositories\SlotsRepository;

class UpdateSlotHandler
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
     * @param UpdateSlot $command
     * @return mixed
     */
    public function handle(UpdateSlot $command)
    {
        $request = $command->request;
        $slot = $command->slot;

        $this->slot->updateRich($request->validated(), $slot->getKey());

        return $this->slot->find($slot->getKey());
    }
}
