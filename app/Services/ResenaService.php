<?php

namespace App\Services;

use App\Models\Resena;
use App\Models\Usuario;
use App\Models\Producto;
use \App\Models\OrdenItem;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class ResenaService
{
  private function usuarioComproProducto(Usuario $usuario, int $productoId): bool
  {
    return OrdenItem::whereHas('orden', function ($query) use ($usuario) {
      $query->where('usuario_id', $usuario->id)
        ->whereIn('estado', ['pagado', 'enviado']);
    })->where('producto_id', $productoId)->exists();
  }

  public function crearResena(Usuario $usuario, int $productoId, int $puntuacion, ?string $comentario): Resena
  {
    $producto = Producto::find($productoId);
    if (!$producto) {
      throw new Exception('Producto no encontrado');
    }

    if (!$this->usuarioComproProducto($usuario, $productoId)) {
      throw new Exception('Solo puedes reseñar productos que hayas comprado');
    }

    $existente = Resena::where('usuario_id', $usuario->id)
      ->where('producto_id', $productoId)
      ->first();
    if ($existente) {
      throw new Exception('Ya has reseñado este producto');
    }

    return Resena::create([
      'usuario_id'  => $usuario->id,
      'producto_id' => $productoId,
      'puntuacion'  => $puntuacion,
      'comentario'  => $comentario,
    ]);
  }

  public function actualizarResena(Usuario $usuario, int $resenaId, int $puntuacion, ?string $comentario): ?Resena
  {
    $resena = Resena::where('id', $resenaId)
      ->where('usuario_id', $usuario->id)
      ->first();

    if (!$resena) {
      return null;
    }

    $resena->puntuacion = $puntuacion;
    $resena->comentario = $comentario;
    $resena->save();

    return $resena;
  }

  public function eliminarResena(Usuario $usuario, int $resenaId): bool
  {
    $resena = Resena::where('id', $resenaId)
      ->where('usuario_id', $usuario->id)
      ->first();

    if (!$resena) {
      return false;
    }

    return $resena->delete();
  }


  public function obtenerResenasProducto(int $productoId): Collection
  {
    return Resena::with('usuario')
      ->where('producto_id', $productoId)
      ->orderBy('created_at', 'desc')
      ->get();
  }


  public function obtenerPromedioProducto(int $productoId): float
  {
    return (float) Resena::where('producto_id', $productoId)->avg('puntuacion') ?: 0.0;
  }
}
