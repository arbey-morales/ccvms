<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    protected $table = 'entidades_federativas';

    protected $fillable = ["clave","nombre"];

    public $timestamps = false;
}
