<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
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

    //Se muestra la pagina de registro del docente
    public function register()
    {
        return view('recognition.register_user');
    }

    //Ruta para que el usuario inicie sesion en el sistema
    public function login(Request $request)
    {
        $id = $request["id"];
        try{
            $user = User::findOrFail($id);
            $code = $request["validatorKey"];

            //\Auth::login($user);
            if($user != null)
            {
                $verify_login_request = $user["request"];
                $tempCode = $user["validatorKey"];
                if($user != null && $verify_login_request == true && $tempCode == $code) {
                    $request->session()->regenerate();
                    $user["request"] = false;
                    $user["validatorKey"] = "";
                    $user->save();
                    \Auth::login($user, true);
                    return redirect()->intended("/home");
                }
            }
        }catch(\Exception $exception){
            return back()->with("error_verificacion", true);
        }

        return back()->with("error_verificacion", true);
    }

    public function logout(Request $request)
    {
        \Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/');
    }
}
