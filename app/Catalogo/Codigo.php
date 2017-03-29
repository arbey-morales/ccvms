<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    protected $table = 'codigos';
    
    protected $fillable = ["id","clave","nombre"];

    public $timestamps = false;
}
