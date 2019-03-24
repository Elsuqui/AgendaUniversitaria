<?php

namespace App\Listeners;

use App\Events\EventoRegistrado;
use App\Mail\ActividadFinalizadaMail;
use App\Mail\NotificacionAgenda;
use App\Notifications\NotiAtMoment;
use App\Notifications\NotiPrioriAlta;
use App\Notifications\NotiPrioriBaja;
use App\Notifications\NotiPrioriMedia;
use App\User;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Date\Date;

class EnviarNotificacionEventoListener
{

    use SerializesModels;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  EventoRegistrado $event
     * @return boolean
     */
    public function handle(EventoRegistrado $event)
    {

        Date::setLocale("es");

        //Manejo el evento de la tarea o actividad registrada
        try {
            //Consulto el docente que se encuentra en el sistema para enviar la notificacion
            $usuario_notificacion = \Auth::user();
            $dia_evento = Date::parse($event->evento->fecha, 'America/Guayaquil');
            $hoy = Date::now('America/Guayaquil');
            $hora_evento = Date::createFromTimeString($event->evento->hora, 'America/Guayaquil');
            $hora_final_evento = Date::createFromTimeString($event->evento->hora_fin, 'America/Guayaquil');
            $dia_evento_completo = $dia_evento->copy()->addHours($hora_evento->hour)->addMinutes($hora_evento->minute);
            $dias_antes_evento = $dia_evento_completo->diffInDays($hoy);
            $restante = $hoy->diffForHumans($dia_evento_completo, true, false);
            //Notificacion de prueba
            $tiempo_prueba = $hoy->copy()->addSecond(20);
            /*dump($dias_antes_evento);
            dump($tiempo_prueba);*/
            /*dump($hora_evento->hour);
            dump($hora_evento->minute);*/
            /*dump($restante);*/
            \Notification::send($usuario_notificacion, (new NotiPrioriAlta($event->evento, $tiempo_prueba, $restante)));
            $fecha_final_evento = $dia_evento->copy()->addHours($hora_final_evento->hour)->addMinutes($hora_final_evento->minute);
            //\Mail::to($usuario_notificacion)->later($fecha_final_evento, new ActividadFinalizadaMail($event->evento));

            if($hoy->lessThan($dia_evento_completo)){
                /*dump("Biiiiien");*/
                switch ($event->evento->importancia) {
                    case 1:
                        //La mas alta
                        //Según el protocolo se debe enviar 3 notificaciones

                        //Un día antes del evento
                        //Si el evento se registra para hoy no se toma en cuenta esta notificacion
                        if($dias_antes_evento > 0){
                            /*dump("dias");*/
                            $tiempo_noti_dia_antes = $dia_evento_completo->copy()->subDay();
                            //\Mail::to($usuario_notificacion)->later($tiempo_noti_dia_antes, new NotificacionAgenda($event->evento));
                            /*dump($tiempo_noti_dia_antes);*/
                            \Notification::send($usuario_notificacion, (new NotiPrioriAlta($event->evento, $tiempo_noti_dia_antes, $restante)));
                        }

                        //El mismo dia dos horas antes
                        $tiempo_noti_horas_antes = $dia_evento_completo->copy()->subHours(2);
                        if($hoy->diffInHours($tiempo_noti_horas_antes) > 2){
                            /*dump("dos horas antes");
                            dump($tiempo_noti_horas_antes);*/
                            \Notification::send($usuario_notificacion, (new NotiPrioriAlta($event->evento, $tiempo_noti_horas_antes, $restante)));
                        }

                        //El mismo dia treinta minutos antes
                        $tiempo_noti_minutos_antes = $dia_evento_completo->copy()->subMinutes(30);
                        if($hoy->diffInMinutes($tiempo_noti_minutos_antes) > 30){
                           /* dump($hoy->diffInMinutes($tiempo_noti_minutos_antes));
                            dump("treinta minutos antes");
                            dump($tiempo_noti_minutos_antes);*/
                            \Notification::send($usuario_notificacion, (new NotiPrioriAlta($event->evento, $tiempo_noti_minutos_antes, $restante)));
                        }

                        break;
                    case 2:
                        //La media
                        //Según el protocolo se debe enviar 2 notificaciones

                        //Un día antes del evento
                        if($dias_antes_evento > 0){
                            /*dump("dias");*/
                            $tiempo_noti_dia_antes = $dia_evento_completo->copy()->subDay();
                            //\Mail::to($usuario_notificacion)->later($tiempo_noti_dia_antes, new NotificacionAgenda($event->evento));
                            /*dump($tiempo_noti_dia_antes);*/
                            \Notification::send($usuario_notificacion, (new NotiPrioriMedia($event->evento, $tiempo_noti_dia_antes, $restante)));
                        }

                        //El mismo dia 1 hora antes
                        $tiempo_noti_horas_antes = $dia_evento_completo->copy()->subHour();
                        if($hoy->diffInHours($tiempo_noti_horas_antes) > 1){
                            /*dump($tiempo_noti_horas_antes);*/
                            \Notification::send($usuario_notificacion, (new NotiPrioriMedia($event->evento, $tiempo_noti_horas_antes, $restante)));
                        }


                        break;
                    case 3:
                        //La mas baja
                        //Según el protocolo se debe enviar 1 notificacion
                        //Un día antes del evento
                        if($dias_antes_evento > 0){
                            /*dump("dias");*/
                            $tiempo_noti_dia_antes = $dia_evento_completo->copy()->subDay();
                            //\Mail::to($usuario_notificacion)->later($tiempo_noti_dia_antes, new NotificacionAgenda($event->evento));
                            /*dump($tiempo_noti_dia_antes);*/
                            \Notification::send($usuario_notificacion, (new NotiPrioriBaja($event->evento, $tiempo_noti_dia_antes, $restante)));
                        }
                        break;
                }

                //Se envia una notificacion al momento de comenzar el evento
                //$dia_evento_completo = $dia_evento->copy()->addHours($hora_evento->hour)->addMinutes($hora_evento->minute);
                /*dump($dia_evento_completo);*/
                \Notification::send($usuario_notificacion, (new NotiAtMoment($event->evento, $dia_evento_completo)));
                /*dump($fecha_final_evento);*/
                \Mail::to($usuario_notificacion)->later($fecha_final_evento, new ActividadFinalizadaMail($event->evento));
            }
            return true;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return false;
        }

    }
}
