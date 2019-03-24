<?php

namespace App\Http\Controllers;

use App\Materia;
use App\User;
use Illuminate\Http\Request;

class MateriaController extends Controller
{

    public function getQuery(){
        return Materia::query();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Obtengo el listado de todas la materias
        $materias = $this->getQuery()->where("estado", "=", "ACTIVO");
        if($request->ajax()){
            return response()->json($materias->get());
        }
        return view("catalogos.materias", ["materias" => $materias->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Se guarda las materias
        try{
            $materia = new Materia($request->all());
            $materia->id_usuario_creacion = \Auth::id();
            $materia->saveOrFail();
        }catch(\Exception $exception){
            throw $exception;
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $materia = Materia::findOrFail($id);
            return response()->json(["materia" => $materia]);
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{

            $materia = Materia::findOrFail($id);
            $materia->nombre = $request->get("nombre");
            $materia->saveOrFail();
        }catch(\Exception $exception){
            throw $exception;
        }

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $materia = Materia::findOrFail($id);
            return response()->json(["exito" => $materia->delete()]);
        }catch(\Exception $exception){
            throw $exception;
        }
    }


    //Pagina de asignacion de materias
    public function asignacionIndex()
    {
        return view("catalogos.asignacion_materias");
    }

    //Asignar Materia a Docente
    public function asignarMateria($id_materia, $id_docente){

    }

    //Obtener listado de los docentes
    public function listadoDocentes(){
        return response()->json(User::whereKeyNot(1)->get());
    }

    public function materiasAsignadas(){
        return response()->json(Materia::whereHas("materias_docente", function($query){
            $query->where("id_usuario", "=", \Auth::id());
        })->get());
    }
}
