<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $table = 'vacunas';
    
    protected $fillable = ["id","clave","nombre","orden_esquema"];

    public $timestamps = false;

    public function vacunasEsquemas()
    {
        return $this->hasMany('App\Catalogo\VacunaEsquema', 'vacunas_id', 'id');
    }
}
