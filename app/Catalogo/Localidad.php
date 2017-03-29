<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    //use SoftDeletes;
    protected $table = 'localidades';
    
    protected $fillable = ["id","clave","nombre","numeroLongitud","numeroLatitud","numeroAltitud","claveCarta","idEntidad","idMunicipio","claveMunicipio","creadoPor","actualizadoPor","creadoAl","modificadoAl","borradoAl"];

    public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'idEntidad', 'id');
	}

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'idMunicipio', 'id');
	}
}
