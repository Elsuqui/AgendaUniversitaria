<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReconocimientoDocenteController extends Controller
{
    //Se muestra la página inicial de reconocimiento de docente
    public function index()
    {
        return view('recognition.face_detector');
    }

    //Metodo para cargar archivo cascada
    public function archivoCascada(){
        return response(file_get_contents(Storage::path('public/modelos_deteccion/haarcascade_frontalface_default.xml')), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
