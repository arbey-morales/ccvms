<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

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

        return view('catalogo.esquema.index')->with('esquemas', $data);
        //return Response::json([ 'data' => $data], HttpResponse::HTTP_OK);
        //return response()->json([ 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $esquema = Esquema::findOrFail($id);  
        $v_e_two = DB::table('vacunas_esquemas AS ve')
                        ->select('ve.id','ve.vacunas_id','ve.esquemas_id','ve.tipo_aplicacion','ve.orden_esquema AS ve_orden_esquema','ve.intervalo_inicio','ve.intervalo_fin','ve.dosis_requerida','ve.fila','ve.columna','v.clave','v.nombre','v.orden_esquema AS v_orden_esquema','v.color_rgb')
                        ->join('vacunas AS v','v.id','=','ve.vacunas_id')
                        ->where('ve.esquemas_id', $id)
                        ->orderBy('v_orden_esquema')
                        ->orderBy('intervalo_inicio')
                        ->orderBy('fila')
                        ->orderBy('columna')
                        ->get(); 
        if ($esquema) {
            return response()->json([ 'data' => $v_e_two, 'esquema' => $esquema ]);
        } else {
           return response()->json([ 'data' => NULL, 'esquema' => NULL]);
        }        
    }
}
