<?php

namespace App\Http\Controllers;

use App\Evento;
use App\MateriaDocente;
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
}
