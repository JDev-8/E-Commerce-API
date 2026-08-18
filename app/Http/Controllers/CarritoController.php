<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarritoItemRequest;
use App\Services\CarritoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CarritoController extends Controller
{
  protected CarritoService $carritoService;

  public function __construct(CarritoService $carritoService)
  {
    $this->carritoService = $carritoService;
  }

  public function index(Request $request): JsonResponse
  {
    $carrito = $this->carritoService->obtenerCarrito($request->user());
    if (!$carrito) {
      return response()->json(['mensaje' => 'Tu carrito esta vacío', 'items' => []]);
    }
    return response()->json($carrito);
  }

  public function store(CarritoItemRequest $request): JsonResponse
  {
    try {
      $result = $this->carritoService->agregarItem(
        $request->user(),
        $request->producto_id,
        $request->cantidad
      );
      return response()->json(['mensaje' => $result['mensaje']]);
    } catch (\Exception $e) {
      return response()->json(['mensaje' => $e->getMessage()], 400);
    }
  }

  public function destroy(Request $request, $itemId): JsonResponse
  {
    $eliminado = $this->carritoService->eliminarItem($request->user(), $itemId);
    if (!$eliminado) {
      return response()->json(['mensaje' => 'Item no encontrado'], 404);
    }
    return response()->json(['mensaje' => 'Producto eliminado del carrito']);
  }
}
