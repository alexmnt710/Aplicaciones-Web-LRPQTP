<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase;

class ClaseController extends Controller
{
    public function inscripcion(Request $request){
        try {
            $clase = new Clase();
            $clase->claseCursoId = $request->cursoId;
            $clase->claseUserId = $request->userId;
            $clase->save();
            return response()->json([
                'success' => true,
                'message' => 'Inscripción exitosa'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error al inscribirse en el curso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
