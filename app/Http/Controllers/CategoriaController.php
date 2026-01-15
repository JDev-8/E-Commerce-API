<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(Categoria::all());
    }

    public function store(CategoriaRequest $request)
    {
        $categoria = Categoria::create($request->validated());

        return response()->json([
          'mensaje' => 'Categoria registrada con éxito',
          'categoria' => $categoria
        ], 201);
    }

    public function update(CategoriaRequest $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        if(!$categoria) return response()->json(['mensaje' => 'Categoria no econtrada.'], 404);

        $categoria->update($request->validated());

        return response()->json([
          'mensaje' => 'Categoria actualizada con éxito.',
          'categoria' => $categoria
        ], 201);
    }

    public function destroy($id)
    {
      $categoria = Categoria::findOrFail($id);

      if($categoria->productos()->count() > 0) return response()->json(['mensaje' => 'no puede eliminar esta categoria porque tiene productos afiliados'], 409);

      $categoria->delete();

      return response()->json(['mensaje' => 'Categoría eliminada con éxito'], 201);
    }
}
