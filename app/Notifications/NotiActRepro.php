<?php

namespace App\Notifications;

use App\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Date\Date;

class NotiActRepro extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable, SerializesModels;

    public $evento;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        try {
            $dia_evento = Date::parse($this->evento->fecha, 'America/Guayaquil');
            $hora_evento = Date::createFromTimeString($this->evento->hora, 'America/Guayaquil');
            $dia_evento_completo = $dia_evento->copy()->addHours($hora_evento->hour)->addMinutes($hora_evento->minute);
            $hoy = Date::now('America/Guayaquil');
            $restante = $hoy->diffForHumans($dia_evento_completo, true, false);
            $this->evento->setAttribute("tiempo_restante", $restante);
            $this->evento->load(["materia_docente.materia"]);
            $data = [
                "evento" => $this->evento->toArray()
            ];
            if($this->evento->estado_control == "CANCELADA"){
                return (new MailMessage())->view('mails.notificacion_agenda', $data)
                    ->subject("Tarea Cancelada!!");
            }
            return (new MailMessage())->view('mails.notificacion_agenda', $data)
                ->subject("Tarea Reprogramada!!");
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function toDatabase($notifiable){
        return [
            "evento" => $this->evento
        ];
    }

    public function toBroadcast($notifiable){
        return [
            "evento" => $this->evento
        ];
    }
}
