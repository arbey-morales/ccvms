<?php

namespace App\Transaccion;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Pedido extends Model
{
    protected $table = 'pedidos';
	
    protected $fillable = ["pedidos_estatales_id","fecha","descripcion"];

	public $timestamps = false;
}
