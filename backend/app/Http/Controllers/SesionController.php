<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;


class SesionController extends Controller
{
    //
    public function login(Request $request)
    {
        try{
            $request->validate([
                'userName' => 'required',
                'password' => 'required',
            ]);
            if (Auth::attempt($request->all())) {
                $user = Auth::user();
                // Creamos un token para el usuario autenticado
                $token = $user->createToken('authToken')->plainTextToken;
            return response(['success'=>true,'message'=> 'Inicio de Sesion', 'token'=>$token], 200);
            } else {
                // La autenticación ha fallado
                return response(['success'=>false, 'message'=>'Credenciales invalidas'], 401);
            }
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
    public function logout(Request $request){

    }
}
