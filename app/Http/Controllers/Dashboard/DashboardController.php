<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth, DB, Input, Response;
use Carbon\Carbon;

use App\Transaccion\Persona;
use App\Catalogo\PersonaVacunaEsquema;
use App\Catalogo\VacunaEsquema;
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
        
        $fecha_edad_inicio = Carbon::today("America/Mexico_City")->subYear($request->edad); 
        $fecha_edad_fin = Carbon::today("America/Mexico_City")->subYear($request->edad)->subYear(1)->addDay(); // Limite un anio antes de la fecha de inicio   01-12-2015
        //var_dump($fecha_edad_fin->format('Y-m-d'),$fecha_edad_inicio->format('Y-m-d'));
       //var_dump($fecha_edad_fin = Carbon::today("America/Mexico_City")->subYear($request->edad)->subYear(1)->addDay(), $fecha_edad_fin); die;
        $personasCapturas = Persona::select('personas.id','personas.fecha_nacimiento','personas.genero')
            ->where('personas.deleted_at',NULL)
            ->whereBetween('fecha_nacimiento', [$fecha_edad_fin, $fecha_edad_inicio]);

        if (Auth::user()->is('captura')) {
            $personasCapturas = $personasCapturas
                ->leftJoin('clues','clues.id','=','personas.clues_id')
                ->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
        } else {
            if ($request->jurisdicciones_id!=0) {
                $personasCapturas = $personasCapturas
                    ->leftJoin('clues','clues.id','=','personas.clues_id')
                    ->where('clues.jurisdicciones_id',$request->jurisdicciones_id);
            }
        }

        if ($request->clues_id!=0) {
            $personasCapturas = $personasCapturas
                ->where('personas.clues_id',$request->clues_id);
        }
        if ($request->municipios_id!=0) {
            $personasCapturas = $personasCapturas
                ->where('personas.municipios_id',$request->municipios_id);
        }   
        // END PARAMETROS GENERALES

        if ($request->vacunas_id!=0) { // SI SELECCIONA UNA  VACUNA EN ESPECIFICO SE CONTEMPLAN AQUÍ
            $personasCapturas = $personasCapturas
                ->leftJoin('personas_vacunas_esquemas', 'personas.id', '=', 'personas_vacunas_esquemas.personas_id')
                ->leftJoin('vacunas_esquemas','vacunas_esquemas.id','=','personas_vacunas_esquemas.vacunas_esquemas_id')
                ->where('vacunas_esquemas.vacunas_id', $request->vacunas_id);
            if ($request->tipo_aplicacion!=0) {  // SI SELECCIONA UN DOSIS ESPECIFICA DE UNA VACUNA SE CONTEMPLAN AQUÍ
                $personasCapturas = $personasCapturas
                    ->where('vacunas_esquemas.tipo_aplicacion', $request->tipo_aplicacion);
            }                
        }

        $personasCoberturas = $personasCapturas;
        $personasCapturas = $personasCapturas
            ->distinct('personas.id')
            ->get();
        $personasCoberturas = $personasCoberturas
            ->distinct('personas.id')
            ->get();

        // ESTRUCTURA DE LA RESPUESTA ESTRUCTURA DE LA RESPUESTA ESTRUCTURA DE LA RESPUESTA ESTRUCTURA DE LA RESPUESTA
        $data = [
            'captura'=>['todos'=>0, 'ninios'=>0, 'ninias'=>0 ],
            'concordancia'=>[],
            'cobertura'=>[ 'todos'=>0, 'ninios'=>0, 'ninias'=>0, 'datos'=>[] ],
            'esquema_completo'=>[]
        ];
        // ESTRUCTURA DE LA RESPUESTA ESTRUCTURA DE LA RESPUESTA ESTRUCTURA DE LA RESPUESTA ESTRUCTURA DE LA RESPUESTA

        // CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS
        foreach ($personasCapturas as $key => $value) {
            $data['captura']['todos'] += 1;
            if($value->genero=='M'){
                $data['captura']['ninios'] += 1;
            }
            if($value->genero=='F'){
                $data['captura']['ninias'] += 1;
            }
        }
        // CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS CAPTURAS

        // COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS
        foreach ($personasCoberturas as $key => $value) { 
            $fna = explode("-", $value->fecha_nacimiento);
            
            $vei = VacunaEsquema::select('id')
                ->where('vacunas_id', $request->vacunas_id)
                ->where('esquemas_id', $fna[0])
                ->where('edad_ideal_anio', $request->edad);
            // $veec = VacunaEsquema::select('id') // PARA ESQUEMAS COMPLETOS
            //     ->where('esquemas_id', $fna[0])
            //     ->where('edad_ideal_anio','<=', $request->edad)->get();
            
            // var_dump(json_encode($vei), json_encode($veec));
            // if($request->tipo_aplicacion!=0)
            //     $vei = $vei->where('tipo_aplicacion', $request->tipo_aplicacion);
            $vei = $vei->get();
            
            $dosisAplicar = count($vei);

            $dosisAplicadas = 0;
            //var_dump($dosisAplicar);
            foreach ($vei as $keyVei => $valueVei) { // Todas
                $pve = DB::table('personas_vacunas_esquemas')
                ->select('id')
                ->where('vacunas_esquemas_id', $valueVei->id)
                ->where('personas_id', $value->id)
                ->where('deleted_at', NULL)
                ->count(); 

                if($pve>0)
                    $dosisAplicadas++;    
            }
            
            if($dosisAplicar<=0){ // NO HAY DOSIS PARA APLICAR A ESA EDAD

            } else {
                if($dosisAplicar==$dosisAplicadas){ // EL INFANTE TIENE LAS "DOSIS" DE LA VACUNA QUE LE CORRESPONDE A SU EDAD
                    $data['cobertura']['todos'] += 1;
                    if($value->genero=='M'){
                        $data['cobertura']['ninios'] += 1;
                    }
                    if($value->genero=='F'){
                        $data['cobertura']['ninias'] += 1;
                    }
                }
            }                        
        }
        // COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS COBERTURAS 


        return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "personas" => $personasCapturas, "total" => count($data)), 200);			

    }

    public function contenedoresBiologico(Request $request) 
    {
        $estatus = EstatusContenedor::where('deleted_at', NULL)->get();
        $tipos = TipoContenedor::where('deleted_at', NULL)->get();
        $st = [];
        foreach ($estatus as $key => $value) {
            $tc = [];
            foreach ($tipos as $tkey => $tvalue) {
                $conte_est_tipo = ContenedorBiologico::select('contenedores.id')
                    ->where('contenedores.deleted_at',NULL)
                    ->where('contenedores.estatus_contenedor_id',$value->id)
                    ->where('contenedores.tipos_contenedores_id',$tvalue->id)
                    ->join('clues','clues.id','=','contenedores.clues_id');
                if (Auth::user()->is('captura')) {
                    $conte_est_tipo = $conte_est_tipo->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
                } else {
                    if ($request->jurisdicciones_id!=0) {
                        $conte_est_tipo = $conte_est_tipo->where('clues.jurisdicciones_id',$request->jurisdicciones_id);
                    }
                }
                
                if ($request->clues_id!=0) {
                    $conte_est_tipo = $conte_est_tipo->where('contenedores.clues_id',$request->clues_is);
                }
                if ($request->municipios_id!=0) {
                    $conte_est_tipo = $conte_est_tipo->where('clues.municipios_id',$request->municipios_id);
                }
                array_push($tc, ['id'=> $tvalue->id,'nombre'=> $tvalue->nombre, 'imagen'=> $tvalue->imagen, 'clave'=> $tvalue->clave, 'total'=> $conte_est_tipo->count()]);
            }

            $conte_est = ContenedorBiologico::select('contenedores.id')
                ->where('contenedores.deleted_at',NULL)
                ->where('contenedores.estatus_contenedor_id',$value->id)
                ->join('clues','clues.id','=','contenedores.clues_id');
            if (Auth::user()->is('captura')) {
                $conte_est = $conte_est->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
            } else {
                if ($request->jurisdicciones_id!=0) {
                    $conte_est = $conte_est->where('clues.jurisdicciones_id',$request->jurisdicciones_id);
                }
            }
            
            if ($request->clues_id!=0) {
                $conte_est = $conte_est->where('contenedores.clues_id',$request->clues_is);
            }
            if ($request->municipios_id!=0) {
                $conte_est = $conte_est->where('clues.municipios_id',$request->municipios_id);
            }
            array_push($st, ['tipos'=>$tc, 'color'=>$value->color, 'total'=> $conte_est->count(),'id'=>$value->id,'descripcion'=>$value->descripcion,'icono'=>$value->icono]);
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

    public function ubicacionContenedores(Request $request) 
    {
        $data = ContenedorBiologico::select('contenedores.*','clues.id as cid','clues.clues','clues.nombre','clues.numero_latitud','clues.numero_longitud','estatus_contenedor.descripcion','estatus_contenedor.icono','estatus_contenedor.color')
            ->where('contenedores.deleted_at',NULL)
            ->join('clues','clues.id','=','contenedores.clues_id')
            ->join('estatus_contenedor','estatus_contenedor.id','=','contenedores.estatus_contenedor_id')
            ->distinct('cid');
    
        if (Auth::user()->is('captura')) {
            $data = $data->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
        } else {
            if ($request->jurisdicciones_id!=0) {
                $data = $data->where('clues.jurisdicciones_id',$request->jurisdicciones_id);
            }
        }
        
        if ($request->clues_id!=0) {
            $data = $data->where('contenedores.clues_id',$request->clues_is);
        }
        if ($request->municipios_id!=0) {
            $data = $data->where('clues.municipios_id',$request->municipios_id);
        }


        $data = $data->get();
        if(!$data){
			return Response::json(array("status" => 204, "messages" => "No hay resultados"),204);
		} 
		else {			
			return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "total" => count($data)), 200);			
		}
    }

}
