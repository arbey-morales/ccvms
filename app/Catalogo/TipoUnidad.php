<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoUnidad extends Model
{
	protected $table = 'tipos_unidad';

    protected $fillable = ["clave","nombre"];

	public $timestamps = false;
}
