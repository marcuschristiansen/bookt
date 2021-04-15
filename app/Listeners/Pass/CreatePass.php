<?php

namespace App\Listeners\Pass;

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
     * @return Pass $pass
     */
    public function handle(SlotCreated $event)
    {
        $pass = $this->pass->create([
            'team_id' => $event->slot->team_id,
            'name' => 'Slot ' . $event->slot->start_time . '-' . $event->slot->end_time
        ]);

        $pass->slots()->sync($event->slot);

        return $pass;
    }
}
