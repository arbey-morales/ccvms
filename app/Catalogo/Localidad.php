<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{    
    protected $table = 'localidades';
    
    protected $fillable = ["clave","nombre","numero_longitud","numero_latitud","numero_altitud","clave_carta","entidades_id","municipios_id","municipios_clave"];

    public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'entidades_id', 'id');
	}

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'municipios_id', 'id');
	}
}
