<?php

namespace App\Modulo;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Operador extends Model
{
    protected $table = 'Operadores';

    protected $fillable = [ 'idSitio', 'nombre', 'paterno', 'materno', 'idLocalidad', 'direccion', 'tipoDocumento', 'numeroDocumento', 'numLicencia', 'creadoUsuario'];

    protected $guarded = ['id'];

    public $timestamps = false;

    public function tipo_documento()
    {
        return $this->hasOne('App\Catalogo\TiposDocumento', 'id', 'tipoDocumento')->where('activo', 1)->where('borrado', 0);
    }
    
    public function localidad()
    {
        return $this->hasOne('App\Catalogo\Localidades', 'id', 'idLocalidad')->where('activo', 1)->select('id', 'idMunicipio', 'nombre')->with('municipio')->with('municipio.estado');
    }

    public function telefonos()
    {
        return $this->hasMany('App\Modulo\TelefonoOperador', 'idOperador', 'id')->where('borrado', 0)->select('id','idOperador','idTipoTelefono','numero','creadoAl')->with('tipoTelefono');
    }

    public function asignacion()
    {
        return $this->hasMany('App\Modulo\Asignacion', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->where('borrado', 0)->where('terminado', 0)->with('unidad');
    }

    public function servicios()
    {
        $inicialDate  = date('Y-m-d H:i:s', strtotime ( '- 10 days' , strtotime ( date('Y-m-d H:i:s') ) ) );
        return $this->hasMany('App\Modulo\Servicio', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->whereBetween('fechaSolicitud', [$inicialDate, date('Y-m-d H:i:s')])->with('cliente')->with('ubicacion')->with('linea')->with('unidad')->orderBy('fechaSolicitud', 'desc');
    }

    public function suspencioneson()
    {
        return $this->hasMany('App\Modulo\SuspencionOperador', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->where('borrado', 0)->where('levantado', 0);
    }

    public function servicioson()
    {
        return $this->hasMany('App\Modulo\Servicio', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->where('cancelado',0)->where('fechaFin',NULL)->orderBy('fechaSolicitud', 'desc');
    }

    public function incidencias()
    {
        return $this->hasMany('App\Modulo\IncidenciaOperador', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->where('borrado',0)->with('motivo')->orderBy('fechaIncidencia', 'desc');
    }

    public function suspenciones()
    {
        return $this->hasMany('App\Modulo\SuspencionOperador', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->where('borrado',0)->orderBy('levantado', 'asc')->with('incidencia')->orderBy('fechaSuspencion', 'desc');
    }

    public function checksin()
    {
        return $this->hasMany('App\Modulo\Servicio', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->where('cancelado',0)->where('fechaFin',NULL)->orderBy('fechaSolicitud', 'desc');
    }

    public function checksLast()
    {
        return $this->hasMany('App\Modulo\CheckOperador', 'idOperador', 'id')->where('idSitio',Auth::user()->idSitio)->where('borrado',0)->with('unidad')->with('recorridos')->orderBy('id', 'desc')->take(30);
    }

}
