<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';
    
    protected $fillable = ["clave","nombre","entidades_id","jurisdicciones_id"];

    public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'entidades_id', 'id');
	}

    public function jurisdiccion(){
		  return $this->belongsTo('App\Catalogo\Jurisdiccion', 'jurisdicciones_id', 'id');
	}
}
