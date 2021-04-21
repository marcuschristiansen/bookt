<?php

namespace App\Jobs\UserProperties;

use App\Models\UserProperty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteUserProperty implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var UserProperty $userProperty
     */
    public UserProperty $userProperty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserProperty $userProperty)
    {
        $this->userProperty = $userProperty;
    }
}
