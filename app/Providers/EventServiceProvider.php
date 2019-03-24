<?php

namespace App\Providers;

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use App\Events\EventoRegistrado;
use App\Listeners\VerificarEnvioNotificaciones;
use App\Listeners\VerificarPostNotificacion;
use App\Listeners\EnviarNotificacionEventoListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EventoRegistrado::class => [
            EnviarNotificacionEventoListener::class
        ],
        NotificationSending::class => [
            VerificarEnvioNotificaciones::class
        ],
        NotificationSent::class => [
            VerificarPostNotificacion::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
