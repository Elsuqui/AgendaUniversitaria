<?php

namespace App\Notifications;

use App\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class NotiPrioriBaja extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable, SerializesModels;

    public $evento;
    public $tiempo_restante;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Evento $evento, $tiempo, $restante)
    {
        //Trato el evento que se desea enviar la notificacion
        $this->evento = $evento;
        $this->tiempo_restante = $restante;
        $this->delay($tiempo);
    }

    public function dontSend($notifiable)
    {
        return ($this->evento->estado_control === 'REPROGRAMADA' || $this->evento->estado_control == "CANCELADA");
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
            $this->evento->setAttribute("tiempo_restante", $this->tiempo_restante);
            $this->evento->load(["materia_docente.materia"]);
            $data = [
                "evento" => $this->evento->toArray()
            ];
            return (new MailMessage())->view('mails.notificacion_agenda', $data)
                ->subject("Notificacion de Agenda");
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /*public function toArray($notifiable)
    {
        return [
            //
        ];
    }*/

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
