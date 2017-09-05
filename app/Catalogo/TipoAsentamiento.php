<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoAsentamiento extends Model
{
	protected $table = 'tipos_asentamiento';

    protected $fillable = ["descripcion"];

	public $timestamps = false;
}
