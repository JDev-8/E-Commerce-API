<?php

namespace App\Services;

use App\Models\Orden;
use App\Models\OrdenItem;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Exception;

class OrdenService
{
  protected CarritoService $carritoService;
  protected PagoService $pagoService;
  protected EnvioService $envioService;

  public function __construct(CarritoService $carritoService, PagoService $pagoService,  EnvioService $envioService)
  {
    $this->carritoService = $carritoService;
    $this->pagoService = $pagoService;
    $this->envioService = $envioService;
  }

  public function procesarCheckout(Usuario $usuario): array
  {
    $carrito = $this->carritoService->obtenerCarrito($usuario);
    if (!$carrito || $carrito->items->isEmpty()) {
      throw new Exception('El carrito está vacío');
    }

    $totalPagar = 0;
    foreach ($carrito->items as $item) {
      if ($item->producto->stock < $item->cantidad) {
        throw new Exception("Stock insuficiente para el producto: {$item->producto->nombre}");
      }
      $totalPagar += $item->producto->precio * $item->cantidad;
    }

    $paymentIntent = $this->pagoService->crearIntentoPago(
      $totalPagar,
      'usd',
      ['user_id' => $usuario->id]
    );

    return [
      'clienteSecreto' => $paymentIntent->client_secret,
      'IntentoPagarId' => $paymentIntent->id,
      'TotalPagar' => $totalPagar,
    ];
  }

  public function confirmarPago(
    Usuario $usuario,
    string $paymentIntentId,
    ?string $direccionEnvio = null,
    ?string $ciudad = null,
    ?string $codigoPostal = null
  ): array {
    $intent = $this->pagoService->verificarPago($paymentIntentId);
    if ($intent->status !== 'succeeded') {
      throw new Exception('El pago no se ha completado');
    }

    $carrito = $this->carritoService->obtenerCarrito($usuario);
    if (!$carrito || $carrito->items->isEmpty()) {
      throw new Exception('No hay carrito para procesar');
    }

    return DB::transaction(function () use ($usuario, $intent, $carrito, $direccionEnvio, $ciudad, $codigoPostal) {
      $orden = Orden::create([
        'usuario_id' => $usuario->id,
        'estado' => 'pagado',
        'pago_total' => $intent->amount,
      ]);

      foreach ($carrito->items as $item) {
        $producto = Producto::lockForUpdate()->find($item->producto_id);
        if ($producto->stock < $item->cantidad) {
          throw new Exception("El producto {$producto->nombre} se agotó mientras pagabas");
        }

        OrdenItem::create([
          'orden_id' => $orden->id,
          'producto_id' => $producto->id,
          'cantidad' => $item->cantidad,
          'pago_momento' => $producto->precio,
        ]);

        $producto->stock -= $item->cantidad;
        $producto->save();
      }

      Pago::create([
        'orden_id' => $orden->id,
        'stripe_transaction_id' => $intent->id,
      ]);

      $this->envioService->crearEnvio($orden, $usuario, $direccionEnvio, $ciudad, $codigoPostal);

      $this->carritoService->vaciarCarrito($usuario);

      return [
        'mensaje' => 'Compra realizada con éxito',
        'orden_id' => $orden->id,
      ];
    });
  }

  public function obtenerOrdenesUsuario(Usuario $usuario)
  {
    return Orden::where('usuario_id', $usuario->id)
      ->with('items.producto')
      ->orderBy('created_at', 'desc')
      ->get();
  }
}
