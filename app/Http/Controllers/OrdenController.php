<?php

namespace App\Http\Controllers;

use App\Services\OrdenService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrdenController extends Controller
{
  protected OrdenService $ordenService;

  public function __construct(OrdenService $ordenService)
  {
    $this->ordenService = $ordenService;
  }

  public function checkout(Request $request): JsonResponse
  {
    try {
      $result = $this->ordenService->procesarCheckout($request->user());
      return response()->json($result);
    } catch (\Exception $e) {
      return response()->json(['mensaje' => $e->getMessage()], 400);
    }
  }

  public function confirmarPago(Request $request): JsonResponse
  {
    $request->validate([
      'payment_intent_id' => 'required|string',
      'direccion_envio'   => 'nullable|string|max:255',
      'ciudad'            => 'nullable|string|max:100',
      'codigo_postal'     => 'nullable|string|max:20',
    ]);

    try {
      $result = $this->ordenService->confirmarPago(
        $request->user(),
        $request->payment_intent_id,
        $request->direccion_envio,
        $request->ciudad,
        $request->codigo_postal
      );
      return response()->json($result, 201);
    } catch (\Exception $e) {
      return response()->json(['mensaje' => $e->getMessage()], 400);
    }
  }

  public function misOrdenes(Request $request): JsonResponse
  {
    $ordenes = $this->ordenService->obtenerOrdenesUsuario($request->user());
    return response()->json($ordenes);
  }
}
