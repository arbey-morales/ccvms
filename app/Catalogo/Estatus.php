<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
	protected $table = 'estatus';

    protected $fillable = ["clave","descripcion"];

	public $timestamps = false;
}
