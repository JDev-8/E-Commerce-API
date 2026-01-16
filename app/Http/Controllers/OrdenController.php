<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Orden_item;
use App\Models\Carrito;
use App\Models\Pago;
use App\Models\Producto;
use Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    public function checkout(Request $request)
    {
        $usuario = $request->user();

        $carrito = Carrito::where('usuario_id', $usuario->id)->with('items.producto')->first();

        if(!$carrito || $carrito->items()->isEmpty()) return response()->json(['mensaje' => 'El carrito está vacío'], 400);

        $totalPagar = 0;

        foreach($carrito->items as $item){
          if($item->producto->stock < $item->cantidad) return response()->json(['mensaje' => "Stock insuficiente para el producto: {$item->product->nombre}"], 400);
           
          $totalPagar += $item->product->precio * $item->cantidad;
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try{
          $intentoPagar = PaymentIntent::create([
            'amount' => $totalPagar,
            'currency' => 'usd',
            'metadata' => ['user_id' => $usuario->id]
          ]);

          return response()->json([
            'clienteSecreto' => $intentoPagar->client_secret,
            'IntentoPagarId' => $intentoPagar->id,
            'TotalPagar' => $totalPagar
          ]);

        }catch(\Exception $e){
          return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function confirmarPago(Request $request)
    {
        $request->validate([
          'payment_intend_id' => 'required|string'
        ]);
    
        $usuario = $request->user();
        $intentoPagarId = $request->payment_intend_id;

        Stripe::setApIKey(env('STRIPE_SECRET'));

        return DB::transaction(function () use ($usuario, $intentoPagarId){
          try{
            $intento = PaymentIntent::retrieve($intentoPagarId);

            if($intento->status !== 'succeeded') return response()->json(['mensaje' => 'El pago no se ha completado.'], 400);

          }catch(\Exception $e){
             return response()->json(['error' => 'Error al conectar con Stripe'], 500);
          }

          $carrito = Carrito::where('usuario_id', $usuario->id)->with('items.producto')->first();

          if(!$carrito || $carrito->items()->isEmpty()) return response()->json(['mensaje' => 'No hay carrito para procesar.'], 400);

          $orden = Orden::create([
            'usuario_id' => $usuario->id,
            'estado' => 'pagado',
            'pago_total' => $intento->amount
          ]);


          foreach($carrito->item() as $item){
            $producto = Producto::lockForUpdate()->find($item->producto_id);

            if($producto->stock < $item->cantidad)  throw new \Exception("El producto {$producto->nombre} se agotó mientras pagabas.");

            Orden_item::create([
              'orden_id' => $orden->id,
              'producto_id' => $producto->id,
              'cantidad' => $item->cantidad,
              'precio' => $producto->precio
            ]);

            $producto->stock -= $item->cantidad;
            $producto->save();
          }

          $carrito->delete();

          return response()->json([
            'mensaje' => 'Compra realizada con éxito',
            'orden_id' => $orden->id
          ], 201);

        });
    }

    public function misOrdenes(Request $request)
    {
      $ordenes = Orden::where('usuario_id', $request->user()->id())
                  ->with('items.producto')
                  ->orderBy('created_at', 'desc')
                  ->get();
            
      return response()->json($ordenes);
    }
}
