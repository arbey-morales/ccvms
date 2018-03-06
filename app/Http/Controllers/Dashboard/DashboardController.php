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
   
    public function vacunacion() 
    {
        $data = EstatusContenedor::all();
        if(!$data){
			return Response::json(array("status" => 204, "messages" => "No hay resultados"),204);
		} 
		else {			
			return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "total" => count($data)), 200);			
		}
    }

    public function contenedoresEstatus() 
    {
        $data = ContenedorBiologico::select(DB::raw(
            '
            count(
                1
            ) as todos,
            count(
                case when contenedores.estatus_contenedor_id = "1" then 1 else null end
            ) as danado,
            count(
                case when contenedores.estatus_contenedor_id = "2" then 1 else null end
            ) as funcionando,
            count(
                case when contenedores.estatus_contenedor_id = "3" then 1 else null end
            ) as desconectado,
            count(
                case when contenedores.estatus_contenedor_id = "4" then 1 else null end
            ) as espera,
            count(
                case when contenedores.estatus_contenedor_id = "5" then 1 else null end
            ) as sindatos
            '
        ))->where('contenedores.deleted_at',NULL);

        if (Auth::user()->is('captura')) {
            $data = $data->join('clues','clues.id','=','contenedores.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion);
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
