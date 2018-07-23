<?php namespace App\Models\ReporteContenedor;

use App\Models\BaseModel;

/**
* Modelo NotificacionesUsuarios
* 
* @package    Plataforma API
* @subpackage Controlador
* @author     Eliecer Ramirez Esquinca <ramirez.esquinca@gmail.com>
* @created    2015-07-20
*
* Modelo `NotificacionesUsuarios`: Manejo de los usuarios
*
*/
class NotificacionesUsuarios extends BaseModel {

    public function Notificaciones(){
    	return $this->belongsTo('App\Models\ReporteContenedor\Notificaciones','notificaciones_id','id');
    }

}