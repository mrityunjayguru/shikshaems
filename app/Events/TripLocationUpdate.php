<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripLocationUpdate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tripId;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($tripId, $data)
    {
        $this->tripId = $tripId;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel("trip.{$this->tripId}");
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'location.update';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return $this->data;
    }
}
