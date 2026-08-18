<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
  protected AuthService $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  public function register(RegisterRequest $request): JsonResponse
  {
    $result = $this->authService->register($request->validated());
    return response()->json([
      'message' => 'Usuario registrado exitosamente',
      'access_token' => $result['access_token'],
      'token_type' => $result['token_type'],
      'usuario' => $result['usuario']
    ], 201);
  }

  public function login(LoginRequest $request): JsonResponse
  {
    $credentials = [
      'nombre_usuario' => $request->nombre_usuario,
      'password' => $request->contrasenia
    ];

    $result = $this->authService->login($credentials);

    if (!$result) {
      return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }


    return response()->json([
      'message' => 'Hola ' . $result['usuario']->nombres,
      'access_token' => $result['access_token'],
      'token_type' => $result['token_type'],
      'user' => $result['usuario']
    ]);
  }

  public function logout(): JsonResponse
  {
    $this->authService->logout(auth()->user());
    return response()->json(['message' => 'Sesión cerrada exitosamente']);
  }
}
