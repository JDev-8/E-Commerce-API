<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
  use HasFactory;

  protected $table = 'envios';

  protected $fillable = [
    'orden_id',
    'direccion_envio',
    'codigo_postal',
    'ciudad',
    'entregado',
  ];

  public function orden()
  {
    return $this->belongsTo(Orden::class);
  }
}
