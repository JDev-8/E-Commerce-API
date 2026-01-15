<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
      'orden_id',
      'stripe_transaction_id'
    ];

    public function orden(){
      return $this->hasMany(Orden::class);
    }
}
