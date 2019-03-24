<?php

namespace App\Mail;

use App\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActividadFinalizadaMail extends Mailable
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
        try{
            return $this->markdown('mails.actividad_finalizada')->with([
                "url_confirmacion" => route("confirmar.evento", ["id" => $this->evento->id]),
                "url_reprogramar" => route("reprogramar.evento", ["id" => $this->evento->id])
            ]);
        }catch(\Exception $exception){
            dd($exception->getMessage());
        }
    }
}
