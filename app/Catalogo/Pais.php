<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';
    
    protected $fillable = ["descripcion","claveISOA2","claveA3","claveN3","prefijoTelefono"];

    public $timestamps = false;
}
