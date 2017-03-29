<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Ageb extends Model
{
    protected $table = 'agebs';
   
    protected $fillable = ["id","idMunicipio","idLocalidad"];

    public $timestamps = false;

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'idMunicipio', 'id');
	}

    public function localidad(){
		  return $this->belongsTo('App\Catalogo\Localidad', 'idLocalidad', 'id');
	}
}
