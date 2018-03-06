<?php

namespace App\Catalogo;

namespace App\Models\Catalogo\RedFrio;
use App\Models\BaseModel;

class Modelo extends BaseModel
{    
    protected $table = 'modelos';
    
    public $timestamps = false;

    public function marca(){
        return $this->belongsTo('App\Models\Catalogo\RedFrio\Marca', 'marcas_id', 'id')->select('id','nombre');
  }
}
