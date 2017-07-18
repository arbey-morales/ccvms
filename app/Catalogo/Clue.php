<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Clue extends Model
{
	protected $table = 'clues';

    protected $fillable = ["servidor","clues","nombre","domicilio","codigo_postal","numero_longitud","numero_latitud","entidades_id","municipios_id","localidades_id","jurisdicciones_id","instituciones_id","tipos_unidades_id","tipologias_id","estatus_id","consultorios","camas","fecha_construccion","fecha_inicio_operacion","telefono1","telefono2","regiones_id","estratos_id"];

	public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'entidades_id', 'id');
	}

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'municipios_id', 'id');
	}

    public function localidad(){
		  return $this->belongsTo('App\Catalogo\Localidad', 'localidades_id', 'id');
	}

    public function jurisdiccion(){
		  return $this->belongsTo('App\Catalogo\Jurisdiccion', 'jurisdicciones_id', 'id');
	}

    public function institucion(){
		  return $this->belongsTo('App\Catalogo\Institucion', 'instituciones_id', 'id');
	}
}
