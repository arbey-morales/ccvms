<?php

namespace App\Transaccion;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Persona extends Model
{
    protected $table = 'personas';
	
    protected $fillable = ["id", "clues_id", "paises_id", "entidad_fererativa_nacimiento_id", "entidad_federativa_domicilio_id", "municipios_id", "localidades_id", "agebs_id", "instituciones_id", "codigos_id", "tipos_parto_id", "folio_certificado_nacimiento", "nombre", "apellido_paterno", "apellido_materno", "curp", "genero", "fecha_nacimiento", "descripcion_domicilio", "calle", "numero", "colonia", "codigo_postal", "telefono_casa", "telefono_celular", "tutor"];

	public $timestamps = false;

    public function clue(){
		  return $this->belongsTo('App\Catalogo\Clue', 'clues_id', 'id');
	}

    public function pais(){
		  return $this->belongsTo('App\Catalogo\Pais', 'paises_id', 'id');
	}

    public function entidadNacimiento(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'entidad_federativa_nacimiento_id', 'id');
	}

    public function entidadDomicilio(){
		  return $this->belongsTo('App\Catalogo\Entidad', 'entidad_federativa_dimicilio_id', 'id');
	}

    public function municipio(){
		  return $this->belongsTo('App\Catalogo\Municipio', 'municipios_id', 'id');
	}

    public function localidad(){
		  return $this->belongsTo('App\Catalogo\Localidad', 'localidades_id', 'id');
	}

    public function ageb(){
		  return $this->belongsTo('App\Catalogo\Ageb', 'agebs_id', 'id');
	}

    public function afiliacion(){
		  return $this->belongsTo('App\Catalogo\Institucion', 'instituciones_id', 'id');
	}

    public function codigo(){
		  return $this->belongsTo('App\Catalogo\Codigo', 'codigos_id', 'id');
	}

    public function tipoParto(){
		  return $this->belongsTo('App\Catalogo\TipoParto', 'tipos_parto_id', 'id');
	}

	public function personasVacunasEsquemas()
    {
        return $this->hasMany('App\Catalogo\PersonaVacunaEsquema', 'personas_id','id')->where('deleted_at', NULL);
    }

}
