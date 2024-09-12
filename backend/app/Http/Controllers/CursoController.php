<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Validator;

class CursoController extends Controller
{
    //
    public function index($curso){
        $cursos = Curso::all();
        return response()->json($cursos);
    }
    public function show($id){
        $curso = Curso::find($id);
    return response()->json($curso);
    }
    public function createCurso(Request $request){
        $validator = Validator::make($request->all(),[
            'cursoName' => 'required',
            'cursoDescripcion' => 'required',
            'cursoNivelId' => 'required',
            'cursoValor' => 'required',
            'cursoRequisito' => 'required',
            'cursoContenido' => 'required',
            'cursoCategoriaId' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }
        $curso = new Curso();
        $curso->cursoName = $request->cursoName;
        $curso->cursoDescripcion = $request->cursoDescripcion;
        $curso->cursoNivelId = $request->cursoNivelId;
        $curso->cursoValor = $request->cursoValor;
        $curso->cursoRequisito = $request->cursoRequisito;
        $curso->cursoContenido = $request->cursoContenido;
        $curso->cursoCategoriaId = $request->cursoCategoriaId;
        $curso->save();
        return response()->json([
            'success' => true,
            'message' => 'Curso creado exitosamente'
        ]);

    }
    public function updateCurso(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'cursoName' => 'required',
            'cursoDescripcion' => 'required',
            'cursoNivelId' => 'required',
            'cursoValor' => 'required',
            'cursoRequisito' => 'required',
            'cursoContenido' => 'required',
            'cursoCategoriaId' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }
        $curso = Curso::find($id);
        if($curso){
            $curso->cursoName = $request->cursoName;
            $curso->cursoDescripcion = $request->cursoDescripcion;
            $curso->cursoNivelId = $request->cursoNivelId;
            $curso->cursoValor = $request->cursoValor;
            $curso->cursoRequisito = $request->cursoRequisito;
            $curso->cursoContenido = $request->cursoContenido;
            $curso->cursoCategoriaId = $request->cursoCategoriaId;
            $curso->save();
            return response()->json([
                'success' => true,
                'message' => 'Curso actualizado exitosamente'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Curso no encontrado'
        ], 404);
    }
    public function deleteCurso($id){
        $curso = Curso::find($id);
        if($curso){
            $curso->delete();
            return response()->json([
                'success' => true,
                'message' => 'Curso eliminado exitosamente'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Curso no encontrado'
        ], 404);
    }

}
