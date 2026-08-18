<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenItem extends Model
{
  use HasFactory;

  protected $table = 'orden_items';

  protected $fillable = [
    'orden_id',
    'producto_id',
    'cantidad',
    'pago_momento'
  ];

  public function producto()
  {
    return $this->belongsTo(Producto::class, 'producto_id');
  }
}
