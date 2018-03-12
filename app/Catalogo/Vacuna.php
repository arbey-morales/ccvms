<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $table = 'vacunas';
    
    protected $fillable = ["via_administracion_id","clave","nombre","orden_esquema","color_rgb","usuario_id"];

    public $timestamps = false;

    public function vacunasEsquemas()
    {
        return $this->hasMany('App\Catalogo\VacunaEsquema', 'vacunas_id', 'id');
    }
}
