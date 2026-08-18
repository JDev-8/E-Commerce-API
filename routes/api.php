<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\EnvioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registrar', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
Route::get('/productos/busqueda/{texto}', [ProductoController::class, 'search']);

Route::get('/categorias', [CategoriaController::class, 'index']);

Route::get('/productos/{id}/resenas', [ResenaController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/user', function (Request $request) {
    return $request->user();
  });

  Route::post('/resenas', [ResenaController::class, 'store']);
  Route::put('/resenas/{id}', [ResenaController::class, 'update']);
  Route::delete('/resenas/{id}', [ResenaController::class, 'destroy']);

  Route::middleware('admin')->group(function () {
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{id}', [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);

    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);

    Route::get('/envios', [EnvioController::class, 'index']);
    Route::get('/envios/{id}', [EnvioController::class, 'show']);
    Route::put('/envios/{id}', [EnvioController::class, 'update']);
  });

  Route::get('/carrito', [CarritoController::class, 'index']);
  Route::post('/carrito', [CarritoController::class, 'store']);
  Route::delete('/carrito/{id}', [CarritoController::class, 'destroy']);

  Route::post('/checkout', [OrdenController::class, 'checkout']);
  Route::post('/checkout/confirm', [OrdenController::class, 'confirmarPago']);
  Route::get('/mis-ordenes', [OrdenController::class, 'misOrdenes']);
});
