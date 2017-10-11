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

    public function localidades()
    {
        return $this->hasMany('App\Catalogo\Localidad', 'municipios_id')->where('deleted_at', NULL)->select('id','clave','nombre','municipios_id');
    }

    public function clues()
    {
        return $this->hasMany('App\Catalogo\Clue', 'municipios_id')->where('clues','like','CSSSA%')->where('deleted_at', NULL)->where('estatus_id', 1)->select('id','clues','nombre','municipios_id');
    }
}
