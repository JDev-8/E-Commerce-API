<?php

namespace App\Http\Controllers;

use App\Services\ResenaService;
use App\Http\Requests\ResenaRequest;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
  protected ResenaService $resenaService;

  public function __construct(ResenaService $resenaService)
  {
    $this->resenaService = $resenaService;
  }

  public function index($productoId)
  {
    $resenas = $this->resenaService->obtenerResenasProducto($productoId);
    $promedio = $this->resenaService->obtenerPromedioProducto($productoId);
    return response()->json([
      'resenas'  => $resenas,
      'promedio' => $promedio,
    ]);
  }

  public function store(ResenaRequest $request)
  {
    try {
      $resena = $this->resenaService->crearResena(
        $request->user(),
        $request->producto_id,
        $request->puntuacion,
        $request->comentario
      );
      return response()->json([
        'mensaje' => 'Reseña creada exitosamente',
        'resena'  => $resena,
      ], 201);
    } catch (\Exception $e) {
      return response()->json(['mensaje' => $e->getMessage()], 400);
    }
  }

  public function update(ResenaRequest $request, $id)
  {
    $resena = $this->resenaService->actualizarResena(
      $request->user(),
      $id,
      $request->puntuacion,
      $request->comentario
    );
    if (!$resena) {
      return response()->json(['mensaje' => 'Reseña no encontrada o no te pertenece'], 404);
    }
    return response()->json([
      'mensaje' => 'Reseña actualizada.',
      'resena'  => $resena,
    ]);
  }

  public function destroy(Request $request, $id)
  {
    $eliminado = $this->resenaService->eliminarResena($request->user(), $id);
    if (!$eliminado) {
      return response()->json(['mensaje' => 'Reseña no encontrada o no te pertenece'], 404);
    }
    return response()->json(['mensaje' => 'Reseña eliminada']);
  }
}
