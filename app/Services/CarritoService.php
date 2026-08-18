<?php

namespace App\Services;

use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Usuario;

class CarritoService
{
  public function obtenerCarrito(Usuario $usuario)
  {
    return Carrito::where('usuario_id', $usuario->id)
      ->with('items.producto')
      ->first();
  }

  public function obtenerOCrearCarrito(Usuario $usuario)
  {
    return Carrito::firstOrCreate(['usuario_id' => $usuario->id]);
  }

  public function agregarItem(Usuario $usuario, int $productoId, int $cantidad): array
  {
    $carrito = $this->obtenerOCrearCarrito($usuario);
    $producto = Producto::find($productoId);
    if (!$producto) {
      throw new \Exception('Producto no encontrado');
    }

    if ($producto->stock < $cantidad) {
      throw new \Exception('No hay suficiente stock disponible');
    }

    $carritoItem = $carrito->items()->where('producto_id', $productoId)->first();

    if ($carritoItem) {
      $nuevaCantidad = $carritoItem->cantidad + $cantidad;
      if ($producto->stock < $nuevaCantidad) {
        throw new \Exception('No puedes añadir más cantidad de la que hay en stock');
      }
      $carritoItem->cantidad = $nuevaCantidad;
      $carritoItem->save();
      return ['mensaje' => 'Cantidad actualizada', 'item' => $carritoItem];
    } else {
      $item = $carrito->items()->create([
        'producto_id' => $productoId,
        'cantidad' => $cantidad
      ]);
      return ['mensaje' => 'Producto agregado al carrito', 'item' => $item];
    }
  }

  public function eliminarItem(Usuario $usuario, int $itemId): bool
  {
    $carrito = $this->obtenerCarrito($usuario);
    if (!$carrito) return false;

    $item = $carrito->items()->where('id', $itemId)->first();
    if (!$item) return false;

    return $item->delete();
  }

  public function vaciarCarrito(Usuario $usuario): void
  {
    $carrito = $this->obtenerCarrito($usuario);
    if ($carrito) {
      $carrito->items()->delete();
    }
  }
}
