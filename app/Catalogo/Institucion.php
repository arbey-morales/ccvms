<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'instituciones';
   
    protected $fillable = ["id","clave","nombreCorto","nombre","creadoPor","actualizadoPor","creadoAl","modificadoAl","borradoAl"];

    public $timestamps = false;
}
