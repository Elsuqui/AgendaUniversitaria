<?php
/**
 * Created by PhpStorm.
 * User: gsuqu
 * Date: 4/2/2019
 * Time: 8:00
 */

namespace App\Services;

use App\Evento;
use App\Events\EventoRegistrado;
use App\Mail\NotificacionAgenda;
use App\MateriaDocente;
use App\Notifications\NotiActRepro;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Jenssegers\Date\Date;

class EventosService
{
    public function __construct()
    {

    }

    /**
     * Funcion para darle formato a la salida de los eventos que se mostraran en el calendario
     *
     * @param $eventos
     * @return array
     */
    public function fomatearEventos($eventos)
    {
        $events = [];
        foreach ($eventos as $evento) {
            $evento_temporal = [
                "title" => $evento->titulo,
                "msg" => $evento->descripcion,
                "color" => $this->getColor($evento->importancia),
                "start" => $evento->fecha->toDateString() . " " . $evento->hora,
                'url' => "eventos/ver/" . $evento->id,
                "allDay" => false
            ];

            array_push($events, $evento_temporal);
        }

        return $events;
    }

    /**
     * Funcion para determinar el color asociado al nivel de importancia que tiene un evento
     *
     * @param $importancia
     * @return string
     */
    public function getColor($importancia)
    {
        $color = "blue";
        switch ($importancia) {
            case 1:
                $color = "red";
                break;
            case 2:
                $color = "yellow";
                break;
            case 3:
                $color = "green";
                break;
        }

        return $color;
    }

    /**
     * Funcion para listar los eventos que tiene un usuario
     *
     * @return array
     */
    public function listarEventos()
    {
        $eventos = $this->getEventos();
        $event_formated = $this->fomatearEventos($eventos->get());

        return $event_formated;
    }

    /**
     * Servicio para listar los eventos del dia
     *
     * @return Evento|\Illuminate\Database\Eloquent\Builder
     */
    public function listarEventosDelDia()
    {
        $pte = new \DateTimeZone('America/Guayaquil');
        $dte = new \DateTime("now", $pte);
        $fecha_con_formato = $dte->format("Y-m-d");
        $eventos = $this->getEventos()->where("fecha", '=', $fecha_con_formato)->get();
        return $eventos;
    }

    /**
     * Funcion para guardar un evento
     *
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function guardarEvento(Request $request)
    {
        $guardado = false;
        $cruce = false;

        try {
            $materia = $request["materia"];
            $evento = new Evento($request->all());
            $evento->id_materia_docente = MateriaDocente::where("id_materia", '=', $materia)
                ->where("id_usuario", '=', \Auth::id())->first()->id;
            $evento->id_usuario_creacion = \Auth::id();
            $evento->estado = "ACTIVO";

            $cruce = $this->verificarCruceHorario($evento->fecha, $evento->hora, $evento->hora_fin);

            if (!$cruce) {
                $guardado = $evento->saveOrFail();
                event(new EventoRegistrado($evento));
                /*\Mail::to(\Auth::user()->email)->send(new NotificacionAgenda($evento));*/
            }
        } catch (\Exception $exception) {
            if ($exception instanceof QueryException) {
                throw $exception;
            }
        }

        $respuesta = [
            "guardado" => $guardado,
            "conflicto" => $cruce
        ];

        return $respuesta;
    }

    /**
     * Funcion para actualizar un evento
     *
     * @param Request $request
     * @param Int $id
     * @return array
     * @throws \Throwable
     */
    public function editarEvento(Request $request, Int $id)
    {
        $actualizado = false;
        $cruce = false;

        try {
            $materia = $request["materia"];
            $evento = Evento::findOrFail($id);
            $evento->id_materia_docente = MateriaDocente::where("id_materia", '=', $materia)
                ->where("id_usuario", '=', \Auth::id())->first()->id;
            $evento->titulo = $request["titulo"];
            $evento->descripcion = $request["descripcion"];
            $evento->aula = $request["aula"];
            $evento->importancia = $request["importancia"];
            $evento->estado = "ACTIVO";
            $evento->id_usuario_edicion = \Auth::id();

            if (Date::parse($evento->hora)->notEqualTo(Date::parse($request["hora"]))) {
                $cruce = $this->verificarCruceHorario($request["fecha"], $request["hora"], $request["hora_fin"]);
                if (!$cruce) {
                    $evento->estado_control = "REPROGRAMADA";
                    $evento->fecha = $request["fecha"];
                    $evento->estado = "INACTIVO";
                    $actualizado = $evento->saveOrFail();
                    \Auth::user()->notify(new NotiActRepro($evento));

                    //Se crea una nueva Actividad
                    $evento_reprogramado = Evento::create([
                        "id_materia_docente" => $evento->id_materia_docente,
                        "titulo" => $evento->titulo,
                        "descripcion" => $evento->descripcion,
                        "aula" => $evento->aula,
                        "fecha" => $evento->fecha,
                        "hora" => $request["hora"],
                        "hora_fin" => $request["hora_fin"],
                        "importancia" => $evento->importancia,
                        "id_usuario_creacion" => \Auth::id()
                    ]);
                    //Se realiza la notifiacion de planificacion de la tarea reprogramada
                    event(new EventoRegistrado($evento_reprogramado));
                }
            } else {
                $actualizado = $evento->saveOrFail();
                \Mail::to(\Auth::user()->email)->send(new NotificacionAgenda($evento));
            }

        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw $exception;
            }
            if ($exception instanceof QueryException) {
                throw $exception;
            }
        }

        $respuesta = [
            "guardado" => $actualizado,
            "conflicto" => $cruce
        ];

        return $respuesta;
    }

    /**
     * Funcion para eliminar un evento
     *
     * @param Int $id
     * @return bool
     * @throws \Throwable
     */
    public function eliminarEvento(Int $id)
    {
        try {
            $evento = Evento::findOrFail($id);
            $evento->estado_control = "CANCELADA";
            $evento->estado = "INACTIVO";
            $evento->saveOrFail();
            \Auth::user()->notify(new NotiActRepro($evento));
            return true;
        } catch (\Exception $exception) {
            if ($exception instanceof QueryException) {
                throw $exception;
            }
        }
    }


    public function verificarCruceHorario($fecha, $hora, $hora_fin)
    {
        /*$evento = Evento::where("fecha", '=', $fecha)
            ->where("hora", '=', $hora)
            ->whereHas("materia_docente", function($query){
                return $query->id_usuario = \Auth::id();
            })->get();*/

        $evento = Evento::where("estado_control", "=", "PENDIENTE")
            ->where("fecha", "=", $fecha)
            ->where(function ($q) use ($fecha, $hora, $hora_fin) {
                $q->where(function ($query) use ($fecha, $hora){
                    $query->where("hora", "<=", $hora);
                    $query->where("hora_fin", ">=", $hora);
                })->orWhere(function($query) use ($hora_fin){
                    $query->where("hora", "<=", $hora_fin);
                    $query->where("hora_fin", ">=", $hora_fin);
                });
            })
            ->whereHas("materia_docente", function ($query) {
                return $query->id_usuario = \Auth::id();
            })->get();

        return (count($evento) > 0);
    }

    public function getEventos()
    {
        $eventos = Evento::with(["materia_docente"])->whereHas("materia_docente", function ($query) {
            $query->where("id_usuario", '=', \Auth::user()->id);
        })->where("estado", "=", "ACTIVO")->orderBy("importancia");

        return $eventos;
    }
}
