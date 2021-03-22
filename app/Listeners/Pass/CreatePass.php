<?php

namespace App\Listeners;

use App\Events\SlotCreated;
use App\Models\Pass;
use App\Repositories\PassRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePass
{
    /**
     * @var Pass $pass
     */
    protected $pass;

    public function __construct(PassRepository $pass)
    {
        $this->pass = $pass;
    }

    /**
     * Handle the event.
     *
     * @param  SlotCreated  $event
     * @return void
     */
    public function handle(SlotCreated $event)
    {
        $this->pass->create([
            'name' => 'Slot ' . $event->slot->start_time . '-' . $event->slot->end_time . '-' . now()
        ]);

        $this->pass->slots()->sync($event->slot->getKey());
    }
}
