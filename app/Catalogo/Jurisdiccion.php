<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Jurisdiccion extends Model
{
    protected $table = 'jurisdicciones';

    protected $fillable = ["clave","nombre","entidades_id"];

    public $timestamps = false;

    public function entidad(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'entidades_id', 'id');
    }
    
    public function municipios()
    {
        return $this->hasMany('App\Catalogo\Municipio', 'jurisdicciones_id')->where('deleted_at', NULL)->select('id','clave','nombre','jurisdicciones_id');
    }
}
