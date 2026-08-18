<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Services\CategoriaService;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
  protected CategoriaService $categoriaService;

  public function __construct(CategoriaService $categoriaService)
  {
    $this->categoriaService = $categoriaService;
  }

  public function index(): JsonResponse
  {
    return response()->json($this->categoriaService->listar());
  }

  public function store(CategoriaRequest $request): JsonResponse
  {
    $categoria = $this->categoriaService->crear($request->validated());
    return response()->json([
      'mensaje' => 'Categoria registrada con éxito',
      'categoria' => $categoria
    ], 201);
  }

  public function update(CategoriaRequest $request, $id): JsonResponse
  {
    $categoria = $this->categoriaService->actualizar($id, $request->validated());
    if (!$categoria) {
      return response()->json(['mensaje' => 'Categoria no encontrada.'], 404);
    }
    return response()->json([
      'mensaje' => 'Categoria actualizada con éxito.',
      'categoria' => $categoria
    ], 201);
  }

  public function destroy($id): JsonResponse
  {
    if ($this->categoriaService->tieneProductosAsociados($id)) {
      return response()->json(['mensaje' => 'No puede eliminar esta categoría porque tiene productos afiliados'], 409);
    }

    $eliminado = $this->categoriaService->eliminar($id);
    if (!$eliminado) {
      return response()->json(['mensaje' => 'Categoria no encontrada'], 404);
    }
    return response()->json(['mensaje' => 'Categoría eliminada con éxito'], 201);
  }
}
