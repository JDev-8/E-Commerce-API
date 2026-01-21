<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
      $usuario = Usuario::create($request->validated());

      $token = $usuario->createToken('auth_token')->plainTextToken;

      return response()->json([
        'message' => 'Usuario registrado exitosamente',
        'access_token' => $token,
        'token_type' => 'Bearer',
        'usuario' => $usuario
      ], 201);
    }

    public function login(LoginRequest $request)
    {
      $credenciales = [
        'nombre_usuario' => $request->nombre_usuario,
        'password' => $request->contrasenia
      ];

      if(!Auth::attempt($credenciales)){
        return response()->json(['message' => 'Credenciales incorrectas'],);
      }

      $usuario = Usuario::where('nombre_usuario', $request['nombre_usuario'])->firstOrFail();

      $token = $usuario->createToken('auth_token')->plainTextToken;

      return response()->json([
        'message' => 'Hola ' . $usuario->nombres,
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $usuario
      ]);
    }

    public function logout()
    {
      if(auth()->user()){
        auth()->user()->tokens()->delete();
      }
      return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}
