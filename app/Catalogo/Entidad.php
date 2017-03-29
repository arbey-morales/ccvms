<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    protected $table = 'entidadesFederativas';

    protected $fillable = ["id","clave","nombre","creadoPor","actualizadoPor","creadoAl","modificadoAl","borradoAl"];

    public $timestamps = false;
}
