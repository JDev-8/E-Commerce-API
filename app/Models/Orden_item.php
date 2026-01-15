<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden_item extends Model
{
    use HasFactory;

    protected $table = 'orden_items';

    protected $fillable = [
      'orden_id',
      'producto_id',
      'cantidad',
      'pago_momento'
    ];

    public function orden(){
      return $this->hasMany(Orden::class);
    }

    public function producto(){
      return $this->hasMany(Producto::class);
    }
}
