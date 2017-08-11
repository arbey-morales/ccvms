<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Tipologia extends Model
{
	protected $table = 'tipologias';

    protected $fillable = ["clave","tipo","nombre","descripcion"];

	public $timestamps = false;
}
