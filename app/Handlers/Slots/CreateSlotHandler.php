<?php

namespace App\Handlers\Slots;

use App\Jobs\Slots\CreateSlot;
use App\Models\Slot;
use App\Repositories\SlotsRepository;

class CreateSlotHandler
{
    /**
     * @var Slot $slot
     */
    protected $slot;

    public function __construct(SlotsRepository $slot)
    {
        $this->slot = $slot;
    }

    /**
     * @param CreateSlot $event
     */
    public function handle(CreateSlot $command)
    {
        $request = $command->request;
        $fields = array_merge($request->all(), [
            'user_id' => auth()->user()
        ]);

        $this->slot->create($fields);
    }
}
