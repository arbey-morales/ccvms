<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoZona extends Model
{
	protected $table = 'tipos_zona';

    protected $fillable = ["descripcion"];

	public $timestamps = false;
}
