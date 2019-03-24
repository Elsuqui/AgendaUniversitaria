<?php

namespace App\Listeners;

use App\Evento;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificarEnvioNotificaciones
{
    public function handle(NotificationSending $event)
    {
        if (method_exists($event->notification, 'dontSend')) {

            return !$event->notification->dontSend($event->notifiable);
        }

        return true;
    }
}
