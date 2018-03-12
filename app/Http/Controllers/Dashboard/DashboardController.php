<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth, DB, Input, Response;
use Carbon\Carbon;

use App\Transaccion\Persona;
use App\Models\Catalogo\RedFrio\EstatusContenedor;
use App\Models\Catalogo\RedFrio\ContenedorBiologico;
use App\Models\Catalogo\RedFrio\TipoContenedor;

class DashboardController extends Controller
{
    public function index(Request $request) 
    {
        if (!$request->ajax()) {           
            return view('dashboard.index');
        }
    }

    public function capturas(Request $request) 
    {
        $hoy = Carbon::today("America/Mexico_City");
        $semana = Carbon::now()->subWeek();
        $mes = Carbon::now()->subMonth();
        
        $data = Persona::select(DB::raw(
            '
            count(
                1
            ) as todos,
            count(
                case when personas.genero = "M" then 1 else null end
            ) as ninos,
            count(
                case when personas.genero = "F" then 1 else null end
            ) as ninas
            '
        ))->where('personas.deleted_at',NULL);

        if (Auth::user()->is('captura')) {
            $data = $data->join('clues','clues.id','=','personas.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
        }

        $data = $data->get();
        
        if(!$data){
            return Response::json(array("status" => 204, "messages" => "No hay resultados"),204);
        } 
        else {			
            return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "total" => count($data)), 200);			
        }
        
    }
   
    public function vacunacion(Request $request) 
    {
        $hoy = Carbon::today("America/Mexico_City");
        $semana = Carbon::now()->subWeek();
        $mes = Carbon::now()->subMonth();
        
        $personas = Persona::select('personas.id','personas.genero')
        ->where('personas.deleted_at',NULL);

        if (Auth::user()->is('captura')) {
            $personas = $personas->leftJoin('clues','clues.id','=','personas.clues_id')
            ->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
        } else {
            if ($request->jurisdicciones_id!=0) {
                $personas = $personas->leftJoin('clues','clues.id','=','personas.clues_id')
                ->where('clues.jurisdicciones_id',$request->jurisdicciones_id);
            }
        }

        if ($request->vacunas_id!=0) {
            $personas = $personas->leftJoin('personas_vacunas_esquemas', 'personas.id', '=', 'personas_vacunas_esquemas.personas_id')
                ->leftJoin('vacunas_esquemas','vacunas_esquemas.id','=','personas_vacunas_esquemas.vacunas_esquemas_id')
                ->where('vacunas_esquemas.vacunas_id', $request->vacunas_id);
            if ($request->tipo_aplicacion!=0) {
                $personas = $personas->where('vacunas_esquemas.tipo_aplicacion', $request->tipo_aplicacion);
            }                
        }

        $data = [
                    'biologico'=>[],
                    'concordancia'=>[],
                    'esquema_completo'=>[]
                ];
        $personas = $personas->distinct('personas.id')->get();
        
        if(!$personas){
            return Response::json(array("status" => 204, "messages" => "No hay resultados"),204);
        } 
        else {
            $data = [
                    'biologico'=>
                        [
                            'todos'=>0,
                            'ninios'=>0,
                            'ninias'=>0
                        ]
                    ];
            foreach ($personas as $key => $value) {
                $data['biologico']['todos'] += 1;
                if($value->genero=='M'){
                    $data['biologico']['ninios'] += 1;
                }
                if($value->genero=='F'){
                    $data['biologico']['ninias'] += 1;
                }
            }
            return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "personas" => $personas, "total" => count($data)), 200);			
        }
    }

    public function contenedoresBiologico() 
    {
        $estatus = EstatusContenedor::where('deleted_at', NULL)->get();
        $tipos = TipoContenedor::where('deleted_at', NULL)->get();
        $st = [];
        foreach ($estatus as $key => $value) {
            $tc = [];
            foreach ($tipos as $tkey => $tvalue) {
                array_push($tc, ['id'=> $tvalue->id,'nombre'=> $tvalue->nombre, 'imagen'=> $tvalue->imagen, 'clave'=> $tvalue->clave, 'total'=> ContenedorBiologico::where('deleted_at',NULL)->where('estatus_contenedor_id',$value->id)->where('tipos_contenedores_id',$tvalue->id)->count()]);
            }
            array_push($st, ['tipos'=>$tc, 'color'=>$value->color, 'total'=>ContenedorBiologico::where('deleted_at',NULL)->where('estatus_contenedor_id',$value->id)->count(),'id'=>$value->id,'descripcion'=>$value->descripcion,'icono'=>$value->icono]);
        }

    

        if (Auth::user()->is('captura')) {
            //$contenedores = $contenedores->join('clues','clues.id','=','contenedores.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
        }

        //$contenedores = $contenedores->where('contenedores.deleted_at',NULL)->get();
        $data = $st;
        if(!$data){
			return Response::json(array("status" => 204, "messages" => "No hay resultados"),204);
		} 
		else {			
			return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "total" => count($data)), 200);			
		}
    }

}
