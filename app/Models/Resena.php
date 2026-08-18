<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
  use HasFactory;

  protected $table = 'resenas';

  protected $fillable = [
    'usuario_id',
    'producto_id',
    'puntuacion',
    'comentario',
  ];

  public function usuario()
  {
    return $this->belongsTo(Usuario::class);
  }

  public function producto()
  {
    return $this->belongsTo(Producto::class);
  }
}
