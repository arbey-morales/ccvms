<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Carbon\Carbon;

use Session;
use App\Catalogo\Esquema;
use App\Catalogo\VacunaEsquema;

class EsquemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = Input::only('q');
        
        if ($parametros['q']) {
             $data =  Esquema::where('descripcion','LIKE',"%".$parametros['q']."%")->with('vacunasEsquemas')->get();
        } else {
             $data =  Esquema::with('vacunasEsquemas')->get();
        }

        return view('catalogo.esquema.index')->with('data', $data);
        //return Response::json([ 'data' => $data], HttpResponse::HTTP_OK);
        //return response()->json([ 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $parametros = Input::only('fecha_nacimiento','id'); 
        $letra_edad = NULL; 
        $letra_years = 'Años'; $letra_months = 'Meses'; $letra_days = 'Días';

        $esquema = Esquema::findOrFail($id);
        
        if($parametros['fecha_nacimiento']){
            $bd = explode("-",$parametros['fecha_nacimiento']); //dd-mm-yyyy
            $ahora = Carbon::now("America/Mexico_City");
            $fecha_nacimiento = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City");
            $intervalo_dias = $fecha_nacimiento->diffInDays($ahora);
            $diff = abs(strtotime(date("Y-m-d")) - strtotime($parametros['fecha_nacimiento']));
            $years = floor($diff / (365*60*60*24)); 
            if($years==1)
                $letra_years = 'Año';           
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            if($months==1)
                $letra_months = 'Mes';
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 
            if($days==1)
                $letra_days = 'Día';           
            $letra_edad = $years.' '.$letra_years.' '.$months.' '.$letra_months.' '.$days.' '.$letra_days;

            $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', $id)
                ->where('ve.intervalo_inicio','<',($intervalo_dias+1))
                ->where('ve.deleted_at', NULL)
                ->where('v.deleted_at', NULL)                
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio')
                ->orderBy('fila')
                ->orderBy('columna')
                ->get(); 
            foreach ($esquema_detalle as $key => $value) {
                $value->int_inicio_normal = $value->intervalo_inicio;
                $value->int_fin_normal = $value->intervalo_fin;
                $value->mayores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)->where('esquemas_id', $id)->where('intervalo_inicio', '>=', $value->intervalo_inicio)->where('id', '!=', $value->id)->where('deleted_at', NULL)->orderBy('intervalo_inicio', 'ASC')->take(1)->get();
                $value->menores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)->where('esquemas_id', $id)->where('intervalo_inicio', '<=', $value->intervalo_inicio)->where('id', '!=', $value->id)->where('deleted_at', NULL)->orderBy('intervalo_inicio', 'DESC')->take(1)->get();
            }
        } else {
            $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', $id)
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio')
                ->orderBy('fila')
                ->orderBy('columna')
                ->get(); 
        }

        if ($request->ajax()) {
            if ($esquema) {
                return response()->json([ 'data' => $esquema_detalle, 'letra_edad' => $letra_edad, 'esquema' => $esquema ]);
            } else {
            return response()->json([ 'data' => NULL, 'edad' => NULL, 'esquema' => NULL]);
            }    
        } else {
            return view('catalogo.esquema.show')->with(['data' => $esquema_detalle, 'letra_edad' => NULL, 'esquema' => $esquema]);
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEquema($year)
    {
        $esquema = Esquema::findOrFail($id);  
        $esquema = DB::table('vacunas_esquemas AS ve')
                        ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.maximo_ideal','ve.dias_agregar_siguiente_dosis','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                        ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                        ->where('ve.esquemas_id', $id)
                        ->orderBy('v_orden_esquema')
                        ->orderBy('intervalo_inicio')
                        ->orderBy('fila')
                        ->orderBy('columna')
                        ->get(); 
        if ($esquema) {
            return response()->json([ 'data' => $esquema, 'esquema' => $esquema ]);
        } else {
           return response()->json([ 'data' => NULL, 'esquema' => NULL]);
        }        
    }
}
