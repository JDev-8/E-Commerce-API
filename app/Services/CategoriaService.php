<?php

namespace App\Services;

use App\Models\Categoria;

class CategoriaService
{
  public function listar()
  {
    return Categoria::all();
  }

  public function crear(array $data): Categoria
  {
    return Categoria::create($data);
  }

  public function actualizar(int $id, array $data): ?Categoria
  {
    $categoria = Categoria::find($id);
    if (!$categoria) return null;
    $categoria->update($data);
    return $categoria;
  }

  public function eliminar(int $id): bool
  {
    $categoria = Categoria::find($id)->with('producto');
    if (!$categoria) return false;
    if ($categoria->producto()->count() > 0) {
      return false;
    }
    return $categoria->delete();
  }

  public function tieneProductosAsociados(int $id): bool
  {
    $categoria = Categoria::find($id)->with('producto');
    return $categoria && $categoria->producto()->count() > 0;
  }
}
