<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'ordenes';

    protected $fillable = [
      'estado',
      'pago_total',
      'usuario_id',
    ];

    public function usuarios(){
      return $this->hasMany(Usuario::class);
    }

    public function orden_item(){
      return $this->belongsTo(Usuario::class);
    }
}
