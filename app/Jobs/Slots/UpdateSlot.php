<?php

namespace App\Jobs\Slots;

use App\Models\Slot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSlot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request $request
     */
    public Request $request;

    /**
     * @var Slot $slot
     */
    public Slot $slot;

    /**
     * UpdateProperty constructor.
     *
     * @param Request $request
     * @param Slot $slot
     */
    public function __construct(Request $request, Slot $slot)
    {
        $this->request = $request;
        $this->slot = $slot;
    }
}
