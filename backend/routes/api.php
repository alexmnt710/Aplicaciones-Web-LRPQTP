<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SesionController;


//rutas publicas
Route::get('/courses/{id?}',[CursoController::class,'home']);
Route::get('/getCategorias/{search?}/',[CategoriaController::class, 'index']);
Route::get('/getNivel',[CategoriaController::class, 'nivelGet']);
Route::post('/login', [SesionController::class, 'login'])->middleware('guest:sanctum');
Route::post('/postUser', [UserController::class,'createUser'])->middleware('guest:sanctum');
Route::get('/getCategoria',[CategoriaController::class, 'getcategoria']);


//ruta para sesion
Route::post('/', function () {
    return response()->json(['message' => 'Hay Sesion', 'success' => true],200);
})->name('home');


Route::middleware(['auth:sanctum','role:admin|teacher'])->group(function () {
    //Rutas user
    Route::get('/getUsers',[UserController::class,'index']);
    
    Route::put('/updateUser/{id}', [UserController::class,'updateUser']);
    Route::delete('/deleteUser/{id}', [UserController::class,'deleteUser']);
    //Rutas curso
    
    Route::post('/postCurso', [CursoController::class,'createCurso']);
    Route::put('/updateCurso/{id}', [CursoController::class,'updateCurso']);
    Route::delete('/deleteCurso/{id}', [CursoController::class,'deleteCurso']);
    //Rutas categoria
    
    Route::post('/postCategoria',[CategoriaController::class, 'createCategoria']);
    Route::put('/updateCategoria/{id}',[CategoriaController::class, 'updateCategoria']);
    Route::delete('/deleteCategoria/{id}',[CategoriaController::class, 'deleteCategoria']);
});
Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    //Rutas user
    Route::get('/getDocentes',[UserController::class,'getDocentes']);
    Route::post('/postDocente', [UserController::class,'createDocente']);
    //rutas estudiantes
    Route::get('/getEstudiantes',[UserController::class,'getEstudiantes']);

});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/checksesion', [SesionController::class, 'checkSession'])->middleware('auth:sanctum');
    Route::get('/getCursos/{search?}/', [CursoController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/logout', [SesionController::class, 'logout'])->middleware('auth:sanctum');
});
