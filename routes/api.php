<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registrar', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
Route::get('/productos/busqueda/{id}', [ProductoController::class, 'search']);

Route::get('/categorias', [CategoriaController::class, 'index']);

Route::middleware('auth:sanctum')->group(function (){
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/user', function (Request $request) {
    return $request->user();
    });
  
  Route::post('/productos', [ProductoController::class, 'store']);
  Route::put('/productos/{id}', [ProductoController::class, 'update']);
  Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
  
  Route::post('/categorias', [CategoriaController::class, 'store']);
  Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
  Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
});