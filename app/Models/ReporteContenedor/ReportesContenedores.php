<?php


namespace App\Models\ReporteContenedor;
use App\Models\BaseModel;

class ReportesContenedores extends BaseModel
{
  public function fallaContenedor(){
		return $this->belongsTo('App\Models\Catalogo\RedFrio\FallaContenedor','fallas_contenedores_id','id');
  }
  public function contenedor(){
		return $this->belongsTo('App\Models\Catalogo\RedFrio\ContenedorBiologico','contenedores_id','id')->with('clue','tipoContenedor');
  }
  public function seguimientosReportesContenedores(){
    return $this->hasMany('App\Models\ReporteContenedor\SeguimientosReportesContenedores','reportes_contenedores_id');
  }
}
