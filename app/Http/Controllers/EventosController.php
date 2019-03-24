<?php

namespace App\Http\Controllers;

use App\Evento;
use App\Events\EventoRegistrado;
use App\MateriaDocente;
use App\Notifications\ActividadCompletada;
use App\Services\EventosService;
use Illuminate\Http\Request;

class EventosController extends Controller
{
    protected $eventService;

    public function __construct(EventosService $eventosService)
    {
        $this->eventService = $eventosService;
    }

    /**
     * Controlador para listar los eventos formateados
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarEventos()
    {
        return response()->json($this->eventService->listarEventos());
    }


    /**
     * Controlador para guardar eventos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function guardarEvento(Request $request)
    {
        if($request["id"]){
            return response()->json($this->eventService->editarEvento($request, $request["id"]));
        }
        else{
            return response()->json($this->eventService->guardarEvento($request));
        }
    }


    /**
     * Controlador para editar eventos
     *
     * @param Request $request
     * @param Int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function editarEvento(Request $request, Int $id)
    {
        return response()->json($this->eventService->editarEvento($request, $id));
    }

    /**
     * Controlador para eliminar eventos
     *
     * @param Int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarEvento(Int $id)
    {
        return response()->json($this->eventService->eliminarEvento($id));
    }

    /**
     * Controlador para listar los eventos del dia
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventos()
    {
        return response()->json($this->eventService->listarEventosDelDia());
    }

    public function ver($id){
        return response()->json(Evento::findOrFail($id)->load(['materia_docente']));
    }

    public function probarNotificacionEvento($id){
        try{
            $evento = Evento::findOrFail($id);
            event(new EventoRegistrado($evento));
        }catch (\Exception $exception){
            throw $exception;
        }

    }

    public function obtenerNotificaciones(){
        try {
            return response()->json(\Auth::user()->unreadNotifications()->get()->toArray());
        }catch(\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function lecturaNotificacionTarea($id){
        try{
            $notificaciones = \Auth::user()->unreadNotifications()->find($id)->MarkAsRead();
            dd($notificaciones);
            return response()->json("correcto");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }


    public function probarBusquedaNotifiacion($id){
        try{
            $notificaciones = \Auth::user()->notifications()->findOrFail($id);
            //$evento = Evento::findOrFail($notificaciones->data["evento"]["id"]);
            $evento = Evento::find(27);
            dd($evento);
            return response()->json("correcto");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    public function confirmarEvento($id){
        //Buscar el evento que se desea confirmar
        try{
            $evento = Evento::findOrFail($id);
            $evento->estado_control = "REALIZADA";
            $evento->estado = "INACTIVO";
            $evento->id_usuario_edicion = \Auth::id();
            $evento->saveOrFail();
            \Auth::user()->notify(new ActividadCompletada($evento));
        }catch(\Exception $exception){
            throw $exception;
        }

        return view("aditional.confirm_event", ["evento" => $evento]);
    }

    public function reprogramarEvento($id){
        try{
            $evento = Evento::findOrFail($id)->load("materia_docente.materia");
            return view("aditional.reprogramar", ["evento" => $evento]);
        }catch(\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    public function realizarReprogramacion(Request $request){
        return response()->json($this->eventService->editarEvento($request, $request["id"]));
    }
}
