<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carritos';

    protected $fillable = [
      'usuario_id'
    ];

    public function usuarios(){
      return $this->hasMany(Usuario::class);
    }

    public function items(){
      return $this->hasMany(Carrito_item::class, 'carrito_id');
    }
}
