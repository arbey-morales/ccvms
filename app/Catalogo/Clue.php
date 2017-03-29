<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Clue extends Model
{
	protected $table = 'clues';

    protected $fillable = ["id","clues","nombre","domicilio","codigoPostal","numeroLongitud","numeroLatitud","idEntidad","idMunicipio","idLocalidad","idjurisdiccion","idInstitucion","idTipoUnidad","idTipologia","isestatus","cosnultorios","camas","fechaConstruccion","fechaInicioOperacion","telefono1","telefono2","idRegion","idEstrato","creadoPor","actualizadoPor","creadoAl","modificadoAl","borradoAl"];

	public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'idEntidad', 'id');
	}

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'idMunicipio', 'id');
	}

    public function localidad(){
		  return $this->belongsTo('App\Catalogo\Localidad', 'idLocalidad', 'id');
	}

    public function jurisdiccion(){
		  return $this->belongsTo('App\Catalogo\Jurisdiccion', 'idJurisdiccion', 'id');
	}

    public function institucion(){
		  return $this->belongsTo('App\Catalogo\Institucion', 'idInstitucion', 'id');
	}
}
