<?php
/**
 * Created by PhpStorm.
 * User: gsuqu
 * Date: 4/2/2019
 * Time: 8:00
 */

namespace App\Services;

use App\Evento;
use App\MateriaDocente;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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
                'url' => "eventos/ver/".$evento->id,
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
                $color = "orange";
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

            $cruce = $this->verificarCruceHorario($evento->fecha, $evento->hora, $evento->id_materia_docente);

            if (!$cruce) {
                $guardado = $evento->saveOrFail();
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

            if($evento->hora != $request["hora"])
            {
                $cruce = $this->verificarCruceHorario($request["fecha"], $request["hora"], $evento->id_materia_docente);
                if (!$cruce) {
                    $evento->fecha = $request["fecha"];
                    $evento->hora = $request["hora"];
                    $actualizado = $evento->saveOrFail();
                }

            }else{
                $actualizado = $evento->saveOrFail();
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
     * @return bool|null
     */
    public function eliminarEvento(Int $id)
    {
        try {
            $evento = Evento::findOrFail($id);
            $eliminado = $evento->delete();
        } catch (\Exception $exception) {
            if ($exception instanceof QueryException) {
                throw $exception;
            }
        }

        return $eliminado;
    }


    public function verificarCruceHorario($fecha, $hora, $materia_docente)
    {
        $evento = Evento::where("fecha", '=', $fecha)
            ->where("hora", '=', $hora)
            ->where("id_materia_docente", '=', $materia_docente)
            ->first();
        return ($evento != null);
    }

    public function getEventos()
    {
        $eventos = Evento::with(["materia_docente"], function ($query) {
            return $query->where("id_usuario", '=', \Auth::user()->id);
        })->orderBy("importancia");

        return $eventos;
    }
}
