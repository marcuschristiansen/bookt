<?php

namespace App\Jobs\Slots;

use App\Models\Calendar;
use App\Models\Slot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteSlot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Slot $slot
     */
    public Slot $slot;

    /**
     * Create a new job instance.
     *
     * @param Slot $slot
     */
    public function __construct(Slot $slot)
    {
        $this->slot = $slot;
    }
}
