<?php

namespace App\Mail;

use App\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jenssegers\Date\Date;

class NotificacionAgenda extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $evento;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $dia_evento = Date::parse($this->evento->fecha, 'America/Guayaquil');
        $hora_evento = Date::createFromTimeString($this->evento->hora, 'America/Guayaquil');
        $dia_evento_completo = $dia_evento->copy()->addHours($hora_evento->hour)->addMinutes($hora_evento->minute);
        $hoy = Date::now('America/Guayaquil');
        $restante = $hoy->diffForHumans($dia_evento_completo, true, false);
        $this->evento->setAttribute("tiempo_restante", $restante);
        $this->evento->load(["materia_docente.materia"]);

        return $this->view('mails.notificacion_agenda');
    }

}
