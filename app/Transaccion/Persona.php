<?php

namespace App\Transaccion;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Persona extends Model
{
    protected $table = 'personas';
	
    protected $fillable = ["clue_id", "pais_id", "entidad_fererativa_nacimiento_id", "entidad_federativa_domicilio_id", "municipio_id", "localidad_id", "ageb_id", "institucion_id", "codigo_id", "tipo_parto_id", "folio_certificado_nacimiento", "nombre", "apellido_paterno", "apellido_materno", "curp", "genero", "fecha_nacimiento", "descripcion_domicilio", "calle", "numero", "colonia", "codigo_postal", "telefono_casa", "telefono_celular", "tutor", "usuario_id"];

	public $timestamps = false;

    public function clue(){
		return $this->belongsTo('App\Catalogo\Clue', 'clues_id', 'id')->select('id','clues','nombre','domicilio');
	}

    public function pais(){
		return $this->belongsTo('App\Catalogo\Pais', 'paises_id', 'id');
	}

    public function entidadNacimiento(){
		return $this->belongsTo('App\Catalogo\Entidad', 'entidades_federativas_nacimiento_id', 'id');
	}

    public function entidadDomicilio(){
		return $this->belongsTo('App\Catalogo\Entidad', 'entidades_federativas_dimicilio_id', 'id');
	}

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'municipios_id', 'id')->select('id','clave','nombre');
	}

    public function localidad(){
		return $this->belongsTo('App\Catalogo\Localidad', 'localidades_id', 'id')->select('id','clave','nombre');
	}

	public function colonia(){
		return $this->belongsTo('App\Catalogo\Colonia', 'colonias_id', 'id')->select('id','nombre')->with('municipio');
	}

    public function ageb(){
		return $this->belongsTo('App\Catalogo\Ageb', 'agebs_id', 'id')->select('id','municipios_id','localidades_id')->with('municipio','localidad');
	}

    public function afiliacion(){
		return $this->belongsTo('App\Catalogo\Institucion', 'instituciones_id', 'id')->select('id','clave','nombre','nombreCorto');
	}

    public function codigo(){
		return $this->belongsTo('App\Catalogo\CodigoCenso', 'codigos_censos_id', 'id')->select('id','clave','nombre');
	}

    public function tipoParto(){
		return $this->belongsTo('App\Catalogo\TipoParto', 'tipos_partos_id', 'id')->select('id','clave','descripcion');
	}

	public function personasVacunasEsquemas()
    {
        return $this->hasMany('App\Catalogo\PersonaVacunaEsquema', 'personas_id')->where('deleted_at', NULL)->select('id','personas_id','vacunas_esquemas_id','fecha_aplicacion','dosis');
    }

	public function aplicaciones()
    {
        return $this->hasMany('App\Catalogo\PersonaVacunaEsquema', 'personas_id')->where('deleted_at', NULL)->select('id','personas_id','vacunas_esquemas_id','fecha_aplicacion','dosis')->with('esquema');
    }

}
