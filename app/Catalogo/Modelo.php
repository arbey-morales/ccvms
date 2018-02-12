<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{    
    protected $table = 'modelos';
    
    public $timestamps = false;

    public function marca(){
        return $this->belongsTo('App\Catalogo\Marca', 'marcas_id', 'id')->select('id','nombre');
  }
}
