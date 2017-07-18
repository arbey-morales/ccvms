<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoParto extends Model
{
    protected $table = 'tipos_partos';

    protected $fillable = ["clave","descripcion","usuario_id"];

    public $timestamps = false;
}
