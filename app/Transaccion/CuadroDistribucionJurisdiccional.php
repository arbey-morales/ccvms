<?php

namespace App\Transaccion;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CuadroDistribucionJurisdiccional extends Model
{
    protected $table = 'cuadro_distribucion_jurisdiccionales';
	
    protected $fillable = ["pedidos_estatales_id","fecha","descripcion"];

	public $timestamps = false;

    public function pedido_estatal(){
		return $this->belongsTo('App\Transaccion\PedidoEstatal', 'pedidos_estatales_id', 'id')->with('proveedor');
	}
	
}
