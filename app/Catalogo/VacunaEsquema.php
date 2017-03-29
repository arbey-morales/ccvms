<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class VacunaEsquema extends Model
{
    protected $table = 'vacunas_esquemas';
    
    protected $fillable = ["id","vacunas_id","esquemas_id","tipo_aplicacion","intervalo","dosis_requerida"];

    public $timestamps = false;

    public function vacuna(){
		  return $this->belongsTo('App\Catalogo\Vacuna', 'vacunas_id', 'id');
	}

    public function esquema(){
		  return $this->belongsTo('App\Catalogo\Esquema', 'esquemas_id', 'id');
	}
}
