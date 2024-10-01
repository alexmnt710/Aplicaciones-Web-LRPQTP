<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Nivel;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    // Obtener todas las categorías
    public function index($search = null){
        if($search){
            $categorias = Categoria::where('categoriaName','like','%'.$search.'%')->paginate(10);
            return response()->json($categorias);
        }else{
            $categorias = Categoria::paginate(10);
            return response()->json($categorias);
        }
    }

    // Crear una nueva categoría
    public function createCategoria(Request $request){
        $validator = Validator::make($request->all(), [
            'categoriaName' => 'required|unique:categorias',
            'categoriaDescripcion' => 'required',
            'categoriaImagen' => 'required'
        ]);

        // Usando operador ternario para validar
        return $validator->fails()
            ? response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
              ], 422)
            : response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'data' => tap(new Categoria(), function($categoria) use ($request) {
                    $categoria->categoriaName = $request->categoriaName;
                    $categoria->categoriaDescripcion = $request->categoriaDescripcion;
                    $categoria->categoriaImagen = $request->categoriaImagen;
                    $categoria->save();
                })
              ]);
    }

    // Actualizar una categoría existente
    public function updateCategoria(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'categoriaName' => 'required|unique:categorias',
            'categoriaDescripcion' => 'required',
            'categoriaImagen' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor revise los campos',
                'errors' => $validator->errors()
            ], 422);
        }
        $categoria = Categoria::find($id);
        if ($categoria) {
            $categoria->categoriaName = $request->categoriaName;
            $categoria->categoriaDescripcion = $request->categoriaDescripcion;
            $categoria->save();
            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => $categoria
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
    }

    // Eliminar una categoría
    public function deleteCategoria($id){
        $categoria = Categoria::find($id);
        
        if ($categoria) {
            $categoria->delete();
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada exitosamente',
                'data' => $categoria
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }
    }
    
    public function nivelGet(){
        $niveles = Nivel::all();
        return response()->json($niveles);
    }

}

