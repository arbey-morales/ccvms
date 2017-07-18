<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class CodigoCenso extends Model
{
    protected $table = 'codigos_censo';
    
    protected $fillable = ["clave","nombre","usuario_id"];

    public $timestamps = false;
}
