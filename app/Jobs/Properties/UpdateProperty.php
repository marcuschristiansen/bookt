<?php

namespace App\Jobs\Properties;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProperty implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request $request
     */
    public Request $request;

    /**
     * @var Property $property
     */
    public Property $property;

    /**
     * UpdateProperty constructor.
     * @param Request $request
     * @param Property $property
     */
    public function __construct(Request $request, Property $property)
    {
        $this->request = $request;
        $this->property = $property;
    }
}
