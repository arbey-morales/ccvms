<?php

namespace App\Transaccion;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DetalleCuadroDistribucionJurisdiccional extends Model
{
    protected $table = 'detalles_cuadro_distribucion_jurisdiccionales';
	
    protected $fillable = ["cuadro_distribucion_jurisdiccionales_id","insumos_id","cantidad","lote","fecha_caducidad","fecha"];

	public $timestamps = false;
	
}
