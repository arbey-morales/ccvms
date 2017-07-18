<?php

namespace App\Transaccion;

use Illuminate\Database\Eloquent\Model;
use Auth;

class PedidoEstatal extends Model
{
    protected $table = 'pedidos_estatales';
	
    protected $fillable = ["proveedores_id","fecha","descripcion","observacion"];

	public $timestamps = false;

	public function proveedor(){
		return $this->belongsTo('App\Catalogo\Proveedor', 'proveedores_id', 'id');
	}

}
