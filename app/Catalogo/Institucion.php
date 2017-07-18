<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'instituciones';
   
    protected $fillable = ["clave","nombreCorto","nombre"];

    public $timestamps = false;
}
