<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Services\ProductoService;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
  protected ProductoService $productoService;

  public function __construct(ProductoService $productoService)
  {
    $this->productoService = $productoService;
  }

  public function index(): JsonResponse
  {
    return response()->json($this->productoService->listar());
  }

  public function show($id): JsonResponse
  {
    $producto = $this->productoService->obtenerPorId($id);
    if (!$producto) {
      return response()->json(['mensaje' => 'Producto no encontrado'], 404);
    }
    return response()->json($producto);
  }

  public function search($texto): JsonResponse
  {
    return response()->json($this->productoService->buscarPorNombre($texto));
  }

  public function store(ProductoRequest $request): JsonResponse
  {
    $producto = $this->productoService->crear($request->validated());
    return response()->json([
      'mensaje' => 'Producto creado con éxito.',
      'producto' => $producto
    ], 201);
  }

  public function update(ProductoRequest $request, $id): JsonResponse
  {
    $producto = $this->productoService->actualizar($id, $request->validated());
    if (!$producto) {
      return response()->json(['mensaje' => 'Producto no encontrado'], 404);
    }
    return response()->json([
      'mensaje' => 'Producto actualizado',
      'producto' => $producto
    ], 201);
  }

  public function destroy($id): JsonResponse
  {
    $eliminado = $this->productoService->eliminar($id);
    if (!$eliminado) {
      return response()->json(['mensaje' => 'Producto no encontrado.'], 404);
    }
    return response()->json(['mensaje' => 'Producto eliminado con éxito.']);
  }
}
