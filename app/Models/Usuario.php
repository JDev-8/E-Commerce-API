<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'correo_electronico',
        'nombre_usuario',
        'telefono',
        'contrasenia',
        'is_admin',
    ];

    protected $hidden = [
        'contrasenia',
        'remember_token',
    ];

    public function getAuthPassword()
    {
      return $this->contrasenia;
    }
}
