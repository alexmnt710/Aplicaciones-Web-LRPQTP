<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Validator;

class CursoController extends Controller
{
    //
    public function index(Request $request, $search = null){
        try {
            if($search){
                $cursos = Curso::where('cursoName','like','%'.$search.'%')->paginate(10);
                return response()->json($cursos);
            }else if($request->has('categoriaId')){
                $cursos = Curso::where('cursoCategoriaId',$request->categoriaId)->paginate(10);
                return response()->json($cursos);
            }else{
                $cursos = Curso::paginate(10);
                return response()->json($cursos);
            }
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los cursos',
                'error' => $e->getMessage()
            ], 500);

        }
        
    }
    public function createCurso(Request $request){
        $validator = Validator::make($request->all(),[
            'cursoName' => 'required',
            'cursoDescripcion' => 'required',
            'cursoNivelId' => 'required',
            'cursoValor' => 'required',
            'cursoRequisito' => 'required',
            'cursoContenido' => 'required',
            'createdBy' => 'required',
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
        $curso->createdBy = $request->createdBy;
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
            $curso->createdBy = $request->createdBy;
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

    public function home($id = null)
    {
        try {
            if ($id) {

                $curso = Curso::with([
                    'categoria:categoriaId,categoriaName,categoriaImagen', // Trae solo categoriaId y categoriaName de la tabla categorías
                    'nivel:nivelId,nivelName' // Trae solo nivelId y nivelName de la tabla niveles
                ])
                ->find($id);
        
                return response()->json($curso);
                
            }else{
                $cursos = Curso::with([
                    'categoria:categoriaId,categoriaName,categoriaImagen', // Trae solo categoriaId y categoriaName de la tabla categorías
                    'nivel:nivelId,nivelName' // Trae solo nivelId y nivelName de la tabla niveles
                ])
                ->latest()
                ->take(10)
                ->get();
        
                return response()->json($cursos);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los cursos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
