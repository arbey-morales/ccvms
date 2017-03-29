<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoParto extends Model
{
    protected $table = 'tipos_parto';

    protected $fillable = ["id","clave","descripcion"];

    public $timestamps = false;
}
