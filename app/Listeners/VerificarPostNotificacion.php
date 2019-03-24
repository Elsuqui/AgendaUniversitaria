<?php

namespace App\Listeners;

use App\Evento;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificarPostNotificacion
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NotificationSent $event
     * @return bool
     */
    public function handle(NotificationSent $event)
    {

    }
}
