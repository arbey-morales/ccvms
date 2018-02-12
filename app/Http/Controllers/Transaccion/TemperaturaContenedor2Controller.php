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
use Excel;
use File;
use Carbon\Carbon;


use Session;
use App\Transaccion\TemperaturaContenedor;
use App\Catalogo\Clue;
use App\Catalogo\ContenedorBiologico;

class TemperaturaContenedor2Controller extends Controller
{
    /**
	 * @api {get}  /temperatura/  1. Index de Temperaturas 
	 * @apiVersion  0.1.0
	 * @apiName     IndexTemperatura/
	 * @apiGroup    Transaccion/Temperatura
	 *
     * @apiParam    {Number}        contenedores_id             Id de  Contenedor seleccionado
     * @apiParam    {Number}        fecha_inicial               Valor tipo fecha: YYYY-MM-DD
     * @apiParam    {Number}        clues_id                    Id de  Clue seleccionada
     * @apiParam    {Number}        fecha_final                 Valor tipo fecha: YYYY-MM-DD
     *
     * @apiSuccess  {Json}          data                        Valores en formato JSON
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
     *       "texto": "Titulo gráfica",
     *       "sub_texto": "Subtitulo gráfica",
     *       "variacion": {"data":[]},
     *       "maxima_minima": {
	 *          "estampas": ['2017-12-26','2017-12-27'],
     *          "maximas": [6,5]
     *          "minimas": [2,1]
     *       }
	 *     } 
	 *
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 404 No encontrado
	 *     {
	 *       "texto": "Titulo gráfica",
     *       "sub_texto": "Subtitulo gráfica",
     *       "variacion": {"data":[]},
     *       "maxima_minima": {
	 *          "estampas": [],
     *          "maximas": []
     *          "minimas": []
     *       }
	 *     }
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
	 * @api {get}   /temperatura/create   2. Crear vista de nueva carga de Temperatura
	 * @apiVersion  0.1.0
	 * @apiName     CreateTemperatura
	 * @apiGroup    Transaccion/Temperatura
     * 
     * @apiSuccess  {View}    create                 Vista alojada en: \resources\views\temperatura\create   
     * 
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
	 * @api {post} /temperatura/store     3. Crear Temperatura desde archivo .txt
	 * @apiVersion  0.1.0
	 * @apiName     StoreTemperatura
	 * @apiGroup    Transaccion/Temperatura
	 *
     * @apiParam    {Request}       request                     Cabeceras de la petición.
	 *
	 * @apiSuccess  {View}          /temperatura/create         Vista para crear Clue
     * 
     * @apiSuccess  {String}        msgGeneral                  Mensaje descriptivo de la operación realizada
     * @apiSuccess  {String}        type                        Tipos válidos: success, error, warning e info
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'msgGeneral'   :  'Operación realizada con éxito',
     *       'type'         :   'success'
	 *     }
	 *
     * @apiError TemperaturaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'msgGeneral'   :  'Ocurrió un error al intentar guardar los datos enviados.',
     *       'type'         :   'error'
	 *     }
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
                'mimes'    => 'El :attribute no es de tipo válido',
                'numeric'  => 'El :attribute debe ser numérico',
                'not_in'   => 'El :attribute no es válido',
            ];

            $rules = [
                'clues_id'                   => 'required|min:1|numeric',
                'contenedores_id'            => 'required|not_in:0,NULL|numeric'
            ];
            
            if(isset($request->desde_archivo) && $request->desde_archivo=="SI"){ // desde archivo txt                
                //$rules['archivo'] = 'required|mimes:csv,xlsx';
            } else { // desde input
                $rules['temperatura'] = 'required|numeric';
            }
            
            $this->validate($request, $rules, $messages);
            try {
                if(isset($request->desde_archivo) && $request->desde_archivo=="SI"){                                
                    //$file = \File::get($request->archivo);

                        //$path = $file->getRealPath();
                        $path = Input::file('archivo')->getRealPath();
            
                        $data = Excel::load($path, function($reader) {
            
                        })->get();
            
                        if(!empty($data) && $data->count()){
            
                            foreach ($data as $key => $value) {
                                var_dump($value);
                                //$insert[] = ['title' => $value->title, 'description' => $value->description];
            
                            }
            
                            if(!empty($insert)){
            
                                DB::table('items')->insert($insert);
            
                                dd('Insert Record successfully.');
            
                            }
            
                        }


                        die;
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
