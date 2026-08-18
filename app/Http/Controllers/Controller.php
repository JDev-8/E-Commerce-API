<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
  version: "1.0.0",
  title: "API E-Commerce Profesional",
  description: "Documentación oficial de la API del sistema de E-Commerce. Incluye gestión de inventario, carrito de compras y pasarela de pagos con Stripe."
)]
#[OA\Server(
  url: "http://127.0.0.1:8000",
  description: "Servidor Local"
)]
#[OA\SecurityScheme(
  securityScheme: "bearerAuth",
  type: "http",
  scheme: "bearer",
  bearerFormat: "JWT",
  description: "Ingresa el token generado en el endpoint de Login."
)]
abstract class Controller {}
