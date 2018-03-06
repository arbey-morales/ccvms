<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class ContenedorBiologico2 extends Model
{
    protected $table = 'contenedores';

    public $timestamps = false;

    public function clue(){
        return $this->belongsTo('App\Catalogo\Clue', 'clues_id', 'id')->select('id','clues','nombre');
    }
    public function tipoContenedor(){
        return $this->belongsTo('App\Catalogo\TipoContenedor', 'tipos_contenedores_id', 'id')->select('id','clave','nombre');
    }
    public function modelo(){
        return $this->belongsTo('App\Catalogo\Modelo', 'modelos_id', 'id')->select('id','marcas_id','nombre')->with('marca');
    }
    public function estatus(){
        return $this->belongsTo('App\Catalogo\EstatusContenedor', 'estatus_contenedor_id', 'id')->select('id','descripcion','icono','color');
    }
}
