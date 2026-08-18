<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductoService
{
  public function listar(int $perPage = 10): LengthAwarePaginator
  {
    return Producto::paginate($perPage);
  }

  public function obtenerPorId(int $id): ?Producto
  {
    return Producto::find($id);
  }

  public function buscarPorNombre(string $texto)
  {
    return Producto::where('nombre', 'like', '%' . $texto . '%')->get();
  }

  public function crear(array $data): Producto
  {
    return Producto::create($data);
  }

  public function actualizar(int $id, array $data): ?Producto
  {
    $producto = $this->obtenerPorId($id);
    if (!$producto) return null;
    $producto->update($data);
    return $producto;
  }

  public function eliminar(int $id): bool
  {
    $producto = $this->obtenerPorId($id);
    if (!$producto) return false;
    return $producto->delete();
  }
}
