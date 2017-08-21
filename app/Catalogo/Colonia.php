<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{    
    protected $table = 'colonias';
    
    protected $fillable = ["codigo_postal","nombre","oficina_postal","entidades_id","municipios_id","asentamientoCPC_id","asentamientos_id","zonas_id","ciudades_id"];

    public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'entidades_id', 'id')->select('id','nombre','clave');
	}

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'municipios_id', 'id')->select('id','nombre','clave');
	}
}
