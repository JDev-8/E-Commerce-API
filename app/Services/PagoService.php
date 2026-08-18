<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;

class PagoService
{
  public function __construct()
  {
    Stripe::setApiKey(env('STRIPE_SECRET'));
  }

  public function crearIntentoPago(int $amount, string $currency = 'usd', array $metadata = []): PaymentIntent
  {
    try {
      return PaymentIntent::create([
        'amount' => $amount,
        'currency' => $currency,
        'payment_method_types' => ['card'],
        'metadata' => $metadata,
      ]);
    } catch (Exception $e) {
      throw new Exception('Error al crear el intento de pago: ' . $e->getMessage());
    }
  }

  public function verificarPago(string $paymentIntentId): PaymentIntent
  {
    try {
      return PaymentIntent::retrieve($paymentIntentId);
    } catch (Exception $e) {
      throw new Exception('Error al verificar el pago: ' . $e->getMessage());
    }
  }
}
