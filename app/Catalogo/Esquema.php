<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Esquema extends Model
{
    protected $table = 'esquemas';
    
    protected $fillable = ["id","descripcion"];

    public $timestamps = false;

    public function vacunasEsquemas()
    {
        return $this->hasMany('App\Catalogo\VacunaEsquema', 'esquemas_id', 'id')->with('vacuna','esquema')->orderBy('intervalo', 'ASC')->orderBy('orden_esquema', 'ASC');
    }
}
