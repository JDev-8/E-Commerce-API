<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use App\Http\Requests\CarritoItemRequest;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $carrito = Carrito::where('usuario_id', $usuario->id)
                    ->with('items.producto')
                    ->first();

        if(!$carrito) return response()->json(['mensaje' => 'Tu carrito esta vacío', 'items' => []]);

        return response()->json($carrito);
    }

    public function store(CarritoItemRequest $request)
    {
      $usuario = $request->user();

      $carrito = Carrito::firstOrCreate(['usuario_id' => $usuario->id]);

      $producto = Producto::find($request->producto_id);

      if($producto->stock < $request->cantidad) return response()->json(['mensaje' => 'No hay suficiente stock disponible.'], 400);

      $carritoItem = $carrito->items()->where('producto_id', $producto->id)->first();

      if($carritoItem){
        $nuevaCantidad = $carritoItem->cantidad + $request->cantidad;

        if($producto->stock < $nuevaCantidad ) return response()->json(['mensaje' => 'No puedes añadir más cantidad de la que hay en stock.'], 400);

        $carritoItem->cantidad = $nuevaCantidad;
        $carritoItem->save();
      }else{
        $carrito->items()->create([
          'producto_id' => $producto->id,
          'cantidad' => $request->cantidad
        ]);

        return response()->json(['mensaje' => 'Producto agregado al carrito']);
      }
    }

    public function destroy(Request $request, $itemId)
    {
        $usuario = $request->user();

        $carrito = Carrito::where('usuario_id', $usuario->id)->first();

        if(!$carrito) return response()->json(['mensaje' => 'Carrito no encontrado'], 404);

        $item = $carrito->items()->where('id', $itemId)->first();

        if(!$item) return response()->json(['message' => 'El producto no está en tu carrito'], 404);

        $item->delete();

        return response()->json(['mensaje' => 'Producto eliminado del carrito']);
    }
}
