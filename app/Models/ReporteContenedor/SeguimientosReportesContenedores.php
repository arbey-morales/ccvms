<?php


namespace App\Models\ReporteContenedor;
use App\Models\BaseModel;

class SeguimientosReportesContenedores extends BaseModel
{
  public function reportesContenedores(){
		return $this->belongsTo('App\Models\ReporteContenedor\ReportesContenedores','reportes_contenedores_id','id');
  }
}
