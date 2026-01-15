<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\ProductoRequest;

class ProductoController extends Controller
{
    public function index()
    {
      $productos = Producto::paginate(10);
      return response()->json($productos);
    }

    public function show($id)
    {
      $producto = Producto::find($id);

      if(!$producto){
        return response()->json(['mensaje' => 'Producto no encontrado'], 404);
      }

      return response()->json($producto);
    }

    public function search($texto){
      $productos = Producto::where('nombre', 'like', '%'. $texto . '%')->get();
      return response()->json($productos);
    }

    public function store(ProductoRequest $request)
    {
      $producto = Producto::create($request->validate());

      return response()->json([
        'mensaje' => 'Producto creado con éxito.',
        'producto' => $producto
      ], 201);
    }

    public function update(ProductoRequest $request, $id)
    {
      $producto = Producto::find($id);

      if(!$producto){
        return response()->json(['mensaje' => 'Producto no encontrado'], 404);
      }

      $producto->update($request->validate());

      return response()->json([
        'mensaje' => 'Producto actualizado',
        'producto' => $producto
      ], 201);
    }

    public function destroy($id)
    {
      $producto = Producto::find($id);

      if(!$producto){
        return response()->json(['mensaje' => 'Producto no encontrado.'], 404);
      }

      $producto->delete();

      return response()->json([
        'mensaje' => 'Producto eliminado con éxtio.'
      ]);
    }
}
