<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
	protected $table = 'servidores';

    protected $fillable = ["nombre","secret_key","tiene_internet","catalogos_actualizados","version","periodo_sincronizacion","principal"];

	public $timestamps = false;
}
