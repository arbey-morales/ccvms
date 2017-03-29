<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    //use SoftDeletes;
    protected $table = 'municipios';
    
    protected $fillable = ["id","clave","nombre","idEntidad","idJurisdiccion","creadoPor","actualizadoPor","creadoAl","modificadoAl","borradoAl"];

    public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'idEntidad', 'id');
	}

    public function jurisdiccion(){
		  return $this->belongsTo('App\Catalogo\Jurisdiccion', 'idJurisdiccion', 'id');
	}
}
