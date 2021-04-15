<?php

namespace App\Jobs\Slots;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSlot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array $slot
     */
    public array $slot;

    /**
     * @var int $calendarId
     */
    public int $calendarId;

    /**
     * Create a new job instance.
     *
     * @param array $slot
     * @param int $calendarId
     */
    public function __construct(array $slot, int $calendarId)
    {
        $this->slot = $slot;
        $this->calendarId = $calendarId;
    }
}
