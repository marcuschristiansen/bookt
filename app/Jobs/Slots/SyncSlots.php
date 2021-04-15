<?php

namespace App\Jobs\Slots;

use App\Models\Calendar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncSlots implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array $slots
     */
    public array $slots;

    /**
     * @var Calendar $calendar
     */
    public Calendar $calendar;

    /**
     * Create a new job instance.
     *
     * @param array $slots
     * @param Calendar $calendar
     */
    public function __construct(array $slots, Calendar $calendar)
    {
        $this->slots = $slots;
        $this->calendar = $calendar;
    }
}
