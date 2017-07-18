<?php

namespace App\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Proveedor extends Model
{
    protected $table = 'proveedores';
	
    protected $fillable = ["nombre"];

	public $timestamps = false;

}
