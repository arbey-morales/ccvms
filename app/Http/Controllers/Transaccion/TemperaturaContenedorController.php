<?php

namespace App\Http\Controllers\Transaccion;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use \Validator,\Hash, \Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Carbon\Carbon;

use Session;
use App\Transaccion\TemperaturaContenedor;
use App\Catalogo\Clue;
use App\Catalogo\ContenedorBiologico;

class TemperaturaContenedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->is('root|red-frio') && Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            
            $parametros = Input::only('fecha_inicial','fecha_final','contenedores_id'); // REQUEST
            $data = collect();
            $estampas = [];
            $maximas = [];
            $minimas = [];
            $texto = '';
            $sub_texto = '';

            if(isset($parametros['contenedores_id'])){
                $contenedor = ContenedorBiologico::find($parametros['contenedores_id']);
                $texto = $contenedor->tipoContenedor->nombre.'('.$contenedor->marca->nombre.'|'.$contenedor->modelo->nombre.'|Serie: '.$contenedor->serie.') CLUE: '.$contenedor->clue->clues;
                $sub_texto = $parametros['fecha_inicial'].' | '.$parametros['fecha_final'];    

                if(Carbon::parse($parametros['fecha_inicial'])->diffInDays(Carbon::parse($parametros['fecha_final']), false)>=0){
                    // Gráfica de máximas y mínimas
                    $dias = Carbon::parse($parametros['fecha_inicial'])->diffInDays(Carbon::parse($parametros['fecha_final']), false);
                    for ($i=0; $i <= $dias; $i++) { 
                        $date = Carbon::parse($parametros['fecha_inicial'])->addDays($i)->format('Y-m-d');
                          
                        $data_max = TemperaturaContenedor::select('temperatura')
                        ->where("contenedores_id", $parametros['contenedores_id'])
                        ->where("deleted_at", NULL)
                        ->where('fecha', $date)
                        ->orderBy("temperatura", "DESC")
                        ->take(1)
                        ->get();
                        $data_min = TemperaturaContenedor::select('temperatura')
                        ->where("contenedores_id", $parametros['contenedores_id'])
                        ->where("deleted_at", NULL)
                        ->where('fecha', $date)
                        ->orderBy("temperatura", "ASC")
                        ->take(1)
                        ->get();
                        array_push($estampas, $date);
                        if (count($data_max)>0) {                                                    
                            array_push($maximas, $data_max[0]->temperatura);
                        } else {                                                    
                            array_push($maximas, '--');
                        }                        
                        if (count($data_min)>0) {                
                            array_push($minimas, $data_min[0]->temperatura);
                        } else {                                                    
                            array_push($minimas, '--');
                        }  
                    } // end for days                
                }

                // Gráfica Variaciones
                $data = TemperaturaContenedor::select('temperatura','fecha','hora')
                ->where("deleted_at", NULL)
                ->where("contenedores_id", $parametros['contenedores_id'])
                ->where('fecha', '>=', $parametros['fecha_inicial'])
                ->where('fecha', '<=', $parametros['fecha_final'])
                ->orderBy("fecha", "ASC")
                ->orderBy("hora", "ASC")
                ->get();
            }

            if ($request->ajax()) {
                return Response::json([ 'data' => ['texto' => $texto, 'sub_texto' => $sub_texto, 'variacion' => ['data' => $data], 'maxima_minima' => ['estampas' => $estampas, 'maximas' => $maximas, 'minimas' => $minimas], 'otra_grafica' => array(0 => 0)]], HttpResponse::HTTP_OK);
            } else {            
                return view('temperatura.index')->with(['data' => $data, 'estampas' => $estampas, 'maximas' => $maximas, 'minimas' => $minimas]);
            }
            } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
     {
        if (Auth::user()->can('create.catalogos') && Auth::user()->activo==1) { 
            return view('temperatura.create');
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msgGeneral = '';
        $type       = 'flash_message_info';
        if (Auth::user()->can('create.catalogos') && Auth::user()->activo==1) { 
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'mimes'    => 'El :attribute debe ser de tipo .txt',
                'numeric'  => 'El :attribute debe ser numérico',
                'not_in'   => 'El :attribute no es válido',
            ];

            $rules = [
                'clues_id'                   => 'required|min:1|numeric',
                'contenedores_id'            => 'required|not_in:0,NULL|numeric'
            ];
            
            if(isset($request->desde_archivo) && $request->desde_archivo=="SI"){ // desde archivo txt                
                $rules['archivo'] = 'required|mimes:txt';
            } else { // desde input
                $rules['temperatura'] = 'required|numeric';
            }
            
            $this->validate($request, $rules, $messages);
            try {
                if(isset($request->desde_archivo) && $request->desde_archivo=="SI"){                                
                    $file = \File::get($request->archivo);
                    $cabeceras = substr($file, 0, strrpos($file, "@HEADER ENDS"));

                    $lecturas = substr($file, strrpos($file, "@HEADER ENDS")+ 14, strlen($file));
                    $limpiar_lectura =  str_replace("\t","",str_replace("\r\n","",$lecturas));
                    $limpiar_lectura = str_replace("\n", "|", $limpiar_lectura);
                    $array_lecturas = explode('"', $limpiar_lectura);
                    $lectura_filtrada = [];
                    foreach ($array_lecturas as $key => $value) {
                        if($value!="" && $value!=null)
                            array_push($lectura_filtrada, str_replace('"','',str_replace(".","-", $value)));
                    }
                    
                    $temperaturas = []; $i = 0; $array_temp = [];
                    foreach ($lectura_filtrada as $key => $value) {
                        if($i==3){
                            $i=0;
                            array_push($temperaturas, array('fecha' => $array_temp[0],'hora' => $array_temp[1],'temperatura' => str_replace("-",".",$array_temp[2]) ));
                            $array_temp = [];
                        }
                        $array_temp[$i] = $value; 
                        $i++;

                    }
                                
                    $clue = Clue::findOrFail($request->clues_id);  
                    $contenedor = ContenedorBiologico::findOrFail($request->contenedores_id);  
                    foreach ($temperaturas as $key => $value) {
                        $value = (object) $value;
                        /** INCREMENT **/
                        $id = '';
                        $incremento = 0;
                        $increment = TemperaturaContenedor::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();
                        if(count($increment)>0){
                            $incremento = $increment[0]->incremento + 1; 
                        } else {                 
                            $incremento = 1;                             
                        }
                        $id = $clue->servidor.''.$incremento; 
                        /** END INCREMENT **/
                        $temperatura                     = new TemperaturaContenedor;
                        $temperatura->id                 = $id;
                        $temperatura->servidor_id        = $clue->servidor;
                        $temperatura->incremento         = $incremento;
                        $temperatura->contenedores_id    = $request->contenedores_id;
                        $temperatura->fecha              = Carbon::parse($value->fecha)->format('Y-m-d');
                        $temperatura->hora               = $value->hora;
                        $temperatura->temperatura        = $value->temperatura;
                        $temperatura->observacion        = $cabeceras;
                        $temperatura->usuario_id         = Auth::user()->email;
                        $temperatura->created_at         = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
                        $temperatura->save();
                    }
                    $msgGeneral = 'Hey! se guardaron los datos';
                    $type       = 'flash_message_ok';
                } else {
                    $clue = Clue::findOrFail($request->clues_id);  
                    $contenedor = ContenedorBiologico::findOrFail($request->contenedores_id);
                    $id = '';
                    $incremento = 0;
                    $increment = TemperaturaContenedor::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();
                    if(count($increment)>0){
                        $incremento = $increment[0]->incremento + 1; 
                    } else {                 
                        $incremento = 1;                             
                    }
                    $id = $clue->servidor.''.$incremento; 
                    /** END INCREMENT **/
                    $temperatura                     = new TemperaturaContenedor;
                    $temperatura->id                 = $id;
                    $temperatura->servidor_id        = $clue->servidor;
                    $temperatura->incremento         = $incremento;
                    $temperatura->contenedores_id    = $request->contenedores_id;
                    $temperatura->fecha              = Carbon::now("America/Mexico_City")->format('Y-m-d');
                    $temperatura->hora               = Carbon::now("America/Mexico_City")->format('H:i:s');
                    $temperatura->temperatura        = $request->temperatura;
                    $temperatura->observacion        = 'Guardado desde formulario';
                    $temperatura->usuario_id         = Auth::user()->email;
                    $temperatura->created_at         = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
                    $temperatura->save();

                    $msgGeneral = 'Hey! se guardaron los datos';
                    $type       = 'flash_message_ok';                     
                }
            } catch(\PDOException $e){
                $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados.'.$e->getMessage();
                $type       = 'flash_message_error';
            }

            Session::flash($type, $msgGeneral);
            return redirect()->back();
            
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }
}
