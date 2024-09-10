<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CategoriaController;

Route::get('/user', function (Request $request) {

    return $request->user();
})->middleware('auth:sanctum');
    //rutas mañosas jeje 
    Route::put('rawr',[UserController::class,'index']);

    //Rutas publicas
    Route::get('/getUsers',[UserController::class,'index']);
    Route::post('/postUser', [UserController::class,'createUser']);
    Route::put('/updateUser/{id}', [UserController::class,'updateUser']);
    Route::delete('/deleteUser/{id}', [UserController::class,'deleteUser']);

    Route::get('/getCursos',[CursoController::class,'index']);
    Route::post('/postCurso', [CursoController::class,'createCurso']);
    Route::put('/updateCurso{id}', [CursoController::class,'updateCurso']);
    Route::delete('/deleteCurso{id}', [CursoController::class,'deleteCurso']);

    Route::get('/getCategorias',[CategoriaController::class, 'index']);
    Route::post('/postCategoria',[CategoriaController::class, 'createCategoria']);
    Route::put('/updateCategoria/{id}',[CategoriaController::class, 'updateCategoria']);
    Route::delete('/deleteCategoria/{id}',[CategoriaController::class, 'deleteCategoria']);

