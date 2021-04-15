<?php

namespace App\Jobs\Calendars;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request $request
     */
    public Request $request;

    /**
     * @var int $propertyId
     */
    public int $propertyId;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param int $propertyId
     */
    public function __construct(Request $request, int $propertyId)
    {
        $this->request = $request;
        $this->propertyId = $propertyId;
    }
}
