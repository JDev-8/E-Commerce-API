<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Services\EnvioService;
use App\Http\Requests\EnvioRequest;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
  protected EnvioService $envioService;

  public function __construct(EnvioService $envioService)
  {
    $this->envioService = $envioService;
  }

  public function index(Request $request)
  {
    $filtros = $request->only(['entregado']);
    $envios = $this->envioService->listarEnvios($filtros);
    return response()->json($envios);
  }

  public function show($id)
  {
    $envio = Envio::with('orden.usuario')->find($id);
    if (!$envio) {
      return response()->json(['mensaje' => 'Envío no encontrado.'], 404);
    }
    return response()->json($envio);
  }

  public function update(EnvioRequest $request, $id)
  {
    $envio = $this->envioService->actualizarEstado($id, $request->entregado);
    if (!$envio) {
      return response()->json(['mensaje' => 'Envío no encontrado.'], 404);
    }
    return response()->json([
      'mensaje' => 'Estado de envío actualizado.',
      'envio'   => $envio,
    ]);
  }
}
