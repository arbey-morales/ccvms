<?php

namespace App\Transaccion;

use Illuminate\Database\Eloquent\Model;

class TemperaturaContenedor extends Model
{
    protected $table = 'temperaturas_contenedores';
	
    public $timestamps = false;
    
    public function contenedor(){
		return $this->belongsTo('App\Catalogo\Contenedor', 'contenedores_id', 'id');
	}

}
