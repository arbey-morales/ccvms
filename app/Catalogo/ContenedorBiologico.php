<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class ContenedorBiologico extends Model
{
    protected $table = 'contenedores';

    public $timestamps = false;

    public function clue(){
        return $this->belongsTo('App\Catalogo\Clue', 'clues_id', 'id')->select('id','clues','nombre');
    }
    public function tipoContenedor(){
        return $this->belongsTo('App\Catalogo\TipoContenedor', 'tipos_contenedores_id', 'id')->select('id','clave','nombre');
    }
    public function marca(){
        return $this->belongsTo('App\Catalogo\Marca', 'marcas_id', 'id')->select('id','nombre');
    }
    public function modelo(){
        return $this->belongsTo('App\Catalogo\Modelo', 'modelos_id', 'id')->select('id','nombre');
    }
    public function estatus(){
        return $this->belongsTo('App\Catalogo\EstatusContenedor', 'estatus_contenedor_id', 'id')->select('id','descripcion','icono','color');
    }
}
