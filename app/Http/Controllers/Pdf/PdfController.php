<?php

namespace App\Http\Controllers\Pdf;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use Input;
use Carbon\Carbon;

use App\Transaccion\Persona;
use App\Catalogo\Vacuna;
use App\Catalogo\Esquema;
use App\Catalogo\VacunaEsquema;
use App\Catalogo\PersonaVacunaEsquema;

class PdfController extends Controller
{
    /**
     * Display a pdf of persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function persona() 
    {
        if (Auth::user()->is('root|admin')) {
            $data = Persona::where('deleted_at', NULL)->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->orderBy('id', 'DESC')->get();
        } else { // Limitar por clues
            $data = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->orderBy('personas.id', 'DESC')->get();
        }
        
        $view =  \View::make('pdf.persona', compact('data'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('LEGAL', 'landscape');
        return $pdf->stream('persona');
    }

    /**
     * Display reports with filters only persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter()
    {
        $parametros = Input::only('clue_id','edad','genero');
        
        $now = Carbon::now();
        if($parametros['edad']>1 && $parametros['edad']<=10){ // Edad especifica fecha actual menos x años atras
            $fecha_nacimiento_inferior = Carbon::create(($now->year - $parametros['edad']), $now->month, $now->day, 0, 0, 0); 
        } else { // Todas las edades desde el 2016-01-01 y tope superior fecha actual
            $fecha_nacimiento_inferior = Carbon::create(2016, 1, 01, 0, 0, 0); 
        }

        if($parametros['genero']=="M" || $parametros['genero']=="F"){ // Género especifico
            $operador_genero = "="; 
        } else { // Todos los géneros
            $operador_genero = "!="; 
        }

        if($parametros['clue_id']>0){ // Clue especifica
            $operador_clue = "="; 
        } else { // Todas las clues
            $operador_clue = "!="; 
        }
        
		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            if (Auth::user()->is('root|admin')) {
                $data = Persona::where('deleted_at', NULL)->whereBetween('fecha_nacimiento', [$fecha_nacimiento_inferior, $now])->where('genero', $operador_genero, $parametros['genero'])->where('clues_id', $operador_clue, $parametros['clue_id'])->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','aplicaciones')->orderBy('id', 'DESC')->get();
            } else { // Limitar por clues
                $data = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->whereBetween('personas.fecha_nacimiento', [$fecha_nacimiento_inferior, $now])->where('personas.genero', $operador_genero, $parametros['genero'])->where('personas.clues_id', $operador_clue, $parametros['clue_id'])->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','aplicaciones')->orderBy('personas.id', 'DESC')->get();
            }
            
            $view =  \View::make('pdf.filtro', compact('data'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('LEGAL', 'landscape');
            return $pdf->stream('filtro-persona');
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Display reports with filters only persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter_aplications()
    {
        $parametros = Input::only('clue_id','municipios_id','localidades_id','edad','genero');
        dd(json_encode($parametros)); die;

		if (Auth::user()->can('show.personas') && Auth::user()->activo==1) {
            if (Auth::user()->is('root|admin')) {
                $data = Persona::where('deleted_at', NULL)->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->orderBy('id', 'DESC')->get();
            } else { // Limitar por clues
                $data = Persona::select('personas.*')->join('clues','clues.id','=','personas.clues_id')->where('clues.jurisdicciones_id', Auth::user()->idJurisdiccion)->where('personas.deleted_at', NULL)->with('clue','municipio','localidad','ageb','afiliacion','codigo','tipoParto','personasVacunasEsquemas')->orderBy('personas.id', 'DESC')->get();
            }

            foreach($data as $index=>$value) {
                $born_date = explode("-", $value->fecha_nacimiento);
                $esquema_persona = VacunaEsquema::select('vacunas_esquemas.id','vacunas_esquemas.tipo_aplicacion','vacunas_esquemas.vacunas_id','vacunas.clave','vacunas.color_rgb')->join('vacunas','vacunas.id','=','vacunas_esquemas.vacunas_id')->where('vacunas_esquemas.esquemas_id', $born_date[0])->orderBy('vacunas.orden_esquema', 'ASC')->orderBy('vacunas_esquemas.intervalo_inicio', 'ASC')->get();
                
                foreach($esquema_persona as $key=>$item) {
                    foreach($value->personasVacunasEsquemas as $k=>$i){
                        $esquema_persona[$key]->date_aplied = NULL;
                        if($i->vacunas_esquemas_id==$item->id){
                            $esquema_persona[$key]->date_aplied = $i->fecha_aplicacion;
                        }
                    }
                }
                $data[$index]->esquema_persona = $esquema_persona;
                unset($data[$index]->personasVacunasEsquemas); // quita esquemas del arreglo principal
            }
            
            $view =  \View::make('pdf.persona', compact('data'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('LEGAL', 'landscape');
            return $pdf->stream('persona');
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

}
