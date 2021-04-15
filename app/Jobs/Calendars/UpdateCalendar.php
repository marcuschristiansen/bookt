<?php

namespace App\Jobs\Calendars;

use App\Models\Calendar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request $request
     */
    public Request $request;

    /**
     * @var Calendar $calendar
     */
    public Calendar $calendar;

    /**
     * UpdateProperty constructor.
     *
     * @param Request $request
     * @param Calendar $calendar
     */
    public function __construct(Request $request, Calendar $calendar)
    {
        $this->request = $request;
        $this->calendar = $calendar;
    }
}
