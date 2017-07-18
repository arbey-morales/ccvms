<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class VacunaEsquema extends Model
{
    protected $table = 'vacunas_esquemas';
    
    protected $fillable = ["vacunas_id","esquemas_id","tipo_aplicacion","intervalo","dosis_requerida","usuario_id"];

    public $timestamps = false;

    public function vacuna(){
		  return $this->belongsTo('App\Catalogo\Vacuna', 'vacunas_id', 'id')->select('id','vias_administracion_id','clave','nombre','orden_esquema','color_rgb')->where('deleted_at', NULL);
	}

    public function esquema(){
		  return $this->belongsTo('App\Catalogo\Esquema', 'esquemas_id', 'id')->select('id','descripcion')->where('deleted_at', NULL);
	}
}
