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
        $esquema = Esquema::findOrFail($id);
        $letra_edad = '';
        
        if($parametros['fecha_nacimiento']){
            $bd = explode("-",$parametros['fecha_nacimiento']); //dd-mm-yyyy
            // PARA CONSEGUIR LAS LETRAS DE LA EDAD
            $ahora = Carbon::now("America/Mexico_City");
            $fecha_nacimiento = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City");         
            $total_anios     = $fecha_nacimiento->diffInYears($ahora);
            $fecha_sin_anios = $fecha_nacimiento->addYears($total_anios);
            $total_meses     = $fecha_sin_anios->diffInMonths($ahora);
            $fecha_sin_meses = $fecha_sin_anios->addMonths($total_meses);
            $total_dias      = $fecha_sin_meses->diffInDays($ahora);
            $fecha_sin_dias  = $fecha_sin_meses->addDays($total_dias);
            $letra_total_anios = 'Años';
            $letra_total_meses = 'Meses';
            $letra_total_dias  = 'Días';
            if($total_anios==1)
                $letra_total_anios = 'Año';           
            if($total_meses==1)
                $letra_total_meses = 'Mes';
            if($total_dias==1)
                $letra_total_dias = 'Día';           
            $letra_edad = $total_anios.' '.$letra_total_anios.' '.$total_meses.' '.$letra_total_meses.' '.$total_dias.' '.$letra_total_dias;
            
            $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                ->select('ve.*','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', $id)
                ->where('ve.deleted_at', NULL)
                ->where('v.deleted_at', NULL)                
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio_anio', 'ASC')
                ->orderBy('intervalo_inicio_mes', 'ASC')
                ->orderBy('intervalo_inicio_dia', 'ASC')
                ->orderBy('fila', 'ASC')
                ->orderBy('columna', 'ASC')
                ->get(); 
                
            $born_date = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City");
            $today     = Carbon::now("America/Mexico_City");

            $collect_esquema_detalle = collect();
            foreach ($esquema_detalle as $key => $value) {
                $fecha = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City")->addYears($value->intervalo_inicio_anio)->addMonths($value->intervalo_inicio_mes)->addDays($value->intervalo_inicio_dia)->subDays($value->margen_anticipacion);
                if($fecha<=$today){                    
                    $mayores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                    ->where('esquemas_id', $id)
                    ->where('id', '!=', $value->id)
                    ->where('deleted_at', NULL)
                    ->orderBy('intervalo_inicio_anio','ASC')
                    ->orderBy('intervalo_inicio_mes','ASC')
                    ->orderBy('intervalo_inicio_dia','ASC')->get();

                    $collect_mayores = collect();
                    $collect_menores = collect();
                    foreach ($mayores as $k_mayores => $v_mayores) { // dosis que son diferentes a la actual de menor a mayor
                        $fecha_mayores = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City")->addYears($v_mayores->intervalo_inicio_anio)->addMonths($v_mayores->intervalo_inicio_mes)->addDays($v_mayores->intervalo_inicio_dia)->subDays($v_mayores->margen_anticipacion);
                        if($fecha_mayores>$fecha){ 
                            $collect_mayores->push($v_mayores);
                        }
                    }

                    $value->mayores = $collect_mayores;

                    $menores = VacunaEsquema::where('vacunas_id', $value->vacunas_id)
                    ->where('esquemas_id', $id)
                    ->where('id', '!=', $value->id)
                    ->where('deleted_at', NULL)
                    ->orderBy('intervalo_inicio_anio','DESC')
                    ->orderBy('intervalo_inicio_mes','DESC')
                    ->orderBy('intervalo_inicio_dia','DESC')->get();

                    foreach ($menores as $k_menores => $v_menores) {
                        $fecha_menores = Carbon::parse($bd[2]."-".$bd[1]."-".$bd[0]." 00:00:00","America/Mexico_City")->addYears($v_menores->intervalo_inicio_anio)->addMonths($v_menores->intervalo_inicio_mes)->addDays($v_menores->intervalo_inicio_dia)->subDays($v_menores->margen_anticipacion);
                        if($fecha_menores<$fecha){ 
                            $collect_menores->push($v_menores);
                        }
                    }
                      
                    $value->menores = $collect_menores;
                    $collect_esquema_detalle->push($value);
                }
            } // End foreach principal
            $esquema_detalle = $collect_esquema_detalle;
        } else {
            $esquema_detalle = DB::table('vacunas_esquemas AS ve')
                ->select('ve.*','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                ->where('ve.esquemas_id', $id)
                ->where('ve.deleted_at', NULL)
                ->where('v.deleted_at', NULL)                
                ->orderBy('v_orden_esquema')
                ->orderBy('intervalo_inicio_anio', 'ASC')
                ->orderBy('intervalo_inicio_mes', 'ASC')
                ->orderBy('intervalo_inicio_dia', 'ASC')
                ->orderBy('fila', 'ASC')
                ->orderBy('columna', 'ASC')
                ->get(); 
        }

        if ($request->ajax()) {
            if ($esquema) {
                return response()->json([ 'data' => $esquema_detalle, 'letra_edad' => $letra_edad, 'esquema' => $esquema ]);
            } else {
            return response()->json([ 'data' => NULL, 'edad' => NULL, 'esquema' => $esquema]);
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
                        ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.edad_ideal','ve.dias_entre_siguiente_dosis','ve.margen_anticipacion','ve.etiqueta_no_ideal','ve.etiqueta_ideal','ve.intervalo_inicio_anio','ve.intervalo_inicio_mes','ve.intervalo_inicio_dia','ve.intervalo_fin_anio','ve.intervalo_fin_mes','ve.intervalo_fin_dia','ve.edad_ideal_anio','ve.edad_ideal_mes','ve.edad_ideal_dia','ve.entre_siguiente_dosis_anio','ve.entre_siguiente_dosis_mes','ve.entre_siguiente_dosis_dia','ve.etiqueta_ideal_anio','ve.etiqueta_ideal_mes','ve.etiqueta_ideal_dia','ve.etiqueta_no_ideal_anio','ve.etiqueta_no_ideal_mes','ve.etiqueta_no_ideal_dia','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
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
