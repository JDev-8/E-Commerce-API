<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  public function register(array $data): array
  {
    $data['contrasenia'] = Hash::make($data['contrasenia']);

    $usuario = Usuario::create($data);
    $token = $usuario->createToken('auth_token')->plainTextToken;

    return [
      'usuario' => $usuario,
      'access_token' => $token,
      'token_type' => 'Bearer',
    ];
  }

  public function login(array $credentials): ?array
  {
    if (!Auth::attempt($credentials)) {
      return null;
    }

    $usuario = Usuario::where('nombre_usuario', $credentials['nombre_usuario'])->firstOrFail();
    $token = $usuario->createToken('auth_token')->plainTextToken;

    return [
      'usuario' => $usuario,
      'access_token' => $token,
      'token_type' => 'Bearer',
    ];
  }

  public function logout($user): void
  {
    if ($user) {
      $user->tokens()->delete();
    }
  }
}
