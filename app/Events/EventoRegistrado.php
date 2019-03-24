<?php

namespace App\Events;

use App\Evento;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventoRegistrado
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $evento;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Evento $evento)
    {
        //Recivo el evento que deseo almacenar
        $this->evento = $evento;
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
