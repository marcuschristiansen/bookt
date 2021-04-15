<?php

namespace App\Events;

use App\Models\Slot;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlotCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Slot $slot;
     */
    public $slot;

    /**
     * SlotCreated constructor.
     *
     * @param Slot $slot
     */
    public function __construct(Slot $slot)
    {
        $this->slot = $slot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
