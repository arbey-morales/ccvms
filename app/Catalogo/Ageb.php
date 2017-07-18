<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Ageb extends Model
{
    protected $table = 'agebs';
   
    protected $fillable = ["municipios_id","localidades_id","usuario_id"];

    public $timestamps = false;

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'municipios_id', 'id')->select('id','clave','nombre');
	}

    public function localidad(){
		  return $this->belongsTo('App\Catalogo\Localidad', 'localidades_id', 'id')->select('id','clave','nombre');
	}
}
