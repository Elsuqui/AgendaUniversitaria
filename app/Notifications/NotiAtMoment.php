<?php

namespace App\Notifications;

use App\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class NotiAtMoment extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable, SerializesModels;

    public $evento;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Evento $evento, $tiempo)
    {
        $this->evento = $evento;
        $this->delay($tiempo);
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

    public function dontSend($notifiable)
    {
        return ($this->evento->estado_control === 'REPROGRAMADA' || $this->evento->estado_control == "CANCELADA");
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return bool|MailMessage
     */
    public function toMail($notifiable)
    {
        try {
            $this->evento->load(["materia_docente.materia"]);
            $data = [
                "evento" => $this->evento->toArray()
            ];
            return (new MailMessage())->view('mails.actividad_a_tiempo', $data)
                ->subject("Comenzando Tarea Planificada!!");
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

    /**
     * @param $notifiable
     * @return array|bool
     */
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
