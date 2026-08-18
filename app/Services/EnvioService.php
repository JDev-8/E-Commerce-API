<?php

namespace App\Services;

use App\Models\Envio;
use App\Models\Orden;
use App\Models\Usuario;
use Exception;

class EnvioService
{
  public function crearEnvio(
    Orden $orden,
    Usuario $usuario,
    ?string $direccionEnvio = null,
    ?string $ciudad = null,
    ?string $codigoPostal = null
  ): Envio {

    $direccionFinal = $direccionEnvio ?? $usuario->direccion;
    $ciudadFinal = $ciudad ?? $usuario->ciudad;
    $codigoPostalFinal = $codigoPostal ?? $usuario->codigo_postal;

    if (empty($direccionFinal) || empty($ciudadFinal) || empty($codigoPostalFinal)) {
      throw new Exception('No podemos procesar el pedido: Faltan datos de envío (dirección, ciudad o código postal).');
    }

    return Envio::create([
      'orden_id'        => $orden->id,
      'direccion_envio' => $direccionFinal,
      'ciudad'          => $ciudadFinal,
      'codigo_postal'   => $codigoPostalFinal,
      'entregado'       => false,
    ]);
  }


  public function actualizarEstado(int $envioId, bool $entregado): ?Envio
  {
    $envio = Envio::find($envioId);
    if (!$envio) {
      return null;
    }

    $envio->entregado = $entregado;
    $envio->save();

    if ($entregado) {
      $orden = $envio->orden;
      if ($orden && $orden->estado === 'pagado') {
        $orden->estado = 'entregado';
        $orden->save();
      }
    }

    return $envio;
  }

  public function listarEnvios(array $filtros = [])
  {
    $query = Envio::with(['orden.usuario']);

    if (isset($filtros['entregado'])) {
      $query->where('entregado', filter_var($filtros['entregado'], FILTER_VALIDATE_BOOLEAN));
    }

    return $query->paginate(15);
  }

  public function obtenerPorOrden(int $ordenId): ?Envio
  {
    return Envio::where('orden_id', $ordenId)->first();
  }
}
