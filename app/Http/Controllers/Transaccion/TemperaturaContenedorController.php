<?php

namespace App\Http\Controllers\Transaccion;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Carbon\Carbon;

use Session;
use App\Transaccion\TemperaturaContenedor;
use App\Catalogo\Clue;

class TemperaturaContenedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1) { 
            $parametros = Input::only('fecha_inicial','fecha_final','por_dia'); 
            if(!isset($parametros['fecha_inicial']))
                $parametros['fecha_inicial'] = date('Y-m-d');
            if(!isset($parametros['fecha_final']))
                $parametros['fecha_final'] = date('Y-m-d');
            if(!isset($parametros['por_dia']))
                $parametros['por_dia'] = false;

            $estampas = [];
            $maximas = [];
            $minimas = [];
            $texto_max_min = ' ';
            if(Carbon::parse($parametros['fecha_inicial'])->diffInDays(Carbon::parse($parametros['fecha_final']), false)<0){
                // buscar del día, porque la fecha inicial es mayor a la final
                array_push($estampas, date('Y-m-d'));
                $texto_max_min = date('Y-m-d').'/'.date('Y-m-d');
                $data = TemperaturaContenedor::select('temperatura','fecha','hora')
                ->where("deleted_at", NULL)
                ->where('fecha', date('Y-m-d'))
                ->orderBy("fecha", "ASC")
                ->orderBy("hora", "ASC")
                ->get();
                $data_max = TemperaturaContenedor::select('temperatura')
                    ->where("deleted_at", NULL)
                    ->where('fecha', date('Y-m-d'))
                    ->orderBy("temperatura", "DESC")
                    ->take(1)
                    ->get();
                $data_min = TemperaturaContenedor::select('temperatura')
                    ->where("deleted_at", NULL)
                    ->where('fecha', date('Y-m-d'))
                    ->orderBy("temperatura", "ASC")
                    ->take(1)
                    ->get();
                if (count($data_max)>0) {
                    array_push($maximas, $data_max[0]->temperatura);
                } else  {
                    array_push($maximas, '-');
                }                     
                if (count($data_min)>0) {    
                    array_push($minimas, $data_min[0]->temperatura);
                } else  {
                    array_push($minimas, '-');
                }
            } else {
                // buscar por rango de fecha maximos y minimos
                if(Carbon::parse($parametros['fecha_inicial'])->diffInDays(Carbon::parse($parametros['fecha_final']), false)==0){
                    array_push($estampas, $parametros['fecha_inicial']);
                    $texto_max_min = $parametros['fecha_inicial'].'/'.$parametros['fecha_inicial'];
                    $data = TemperaturaContenedor::select('temperatura','fecha','hora')
                    ->where("deleted_at", NULL)
                    ->where('fecha', $parametros['fecha_inicial'])
                    ->orderBy("fecha", "ASC")
                    ->orderBy("hora", "ASC")
                    ->get();
                    $data_max = TemperaturaContenedor::select('temperatura')
                        ->where("deleted_at", NULL)
                        ->where('fecha', $parametros['fecha_inicial'])
                        ->orderBy("temperatura", "DESC")
                        ->take(1)
                        ->get();
                    $data_min = TemperaturaContenedor::select('temperatura')
                        ->where("deleted_at", NULL)
                        ->where('fecha', $parametros['fecha_inicial'])
                        ->orderBy("temperatura", "ASC")
                        ->take(1)
                        ->get();
                    if (count($data_max)>0) {
                        array_push($maximas, $data_max[0]->temperatura);
                    } else  {
                        array_push($maximas, '-');
                    }                       
                    if (count($data_min)>0) {    
                        array_push($minimas, $data_min[0]->temperatura);
                    } else  {
                        array_push($minimas, '-');
                    }
                } else {
                    $dias = Carbon::parse($parametros['fecha_inicial'])->diffInDays(Carbon::parse($parametros['fecha_final']), false);
                    $data = collect();
                    for ($i=0; $i <= $dias; $i++) { 
                        $date = Carbon::parse($parametros['fecha_inicial'])->addDays($i)->format('Y-m-d');
                        array_push($estampas, $date);
                        $texto_max_min = $date.'/'.$date;
                        $data_temp = TemperaturaContenedor::select('temperatura','fecha','hora')
                        ->where("deleted_at", NULL)
                        ->where('fecha', $date)
                        ->orderBy("fecha", "ASC")
                        ->orderBy("hora", "ASC")
                        ->get();
                        $data->push($data_temp);
                        $data_max = TemperaturaContenedor::select('temperatura')
                            ->where("deleted_at", NULL)
                            ->where('fecha', $date)
                            ->orderBy("temperatura", "DESC")
                            ->take(1)
                            ->get();
                        $data_min = TemperaturaContenedor::select('temperatura')
                            ->where("deleted_at", NULL)
                            ->where('fecha', $date)
                            ->orderBy("temperatura", "ASC")
                            ->take(1)
                            ->get();
                        if (count($data_max)>0) {
                            array_push($maximas, $data_max[0]->temperatura);
                        } else  {
                            array_push($maximas, '-');
                        }                         
                        if (count($data_min)>0) {    
                            array_push($minimas, $data_min[0]->temperatura);
                        } else  {
                            array_push($minimas, '-');
                        } 
                    }
                }
            }
                        
            return view('temperatura.index')->with(['data' => $data, 'texto_max_min' => $texto_max_min, 'estampas' => $estampas, 'maximas' => $maximas, 'minimas' => $minimas, 'fecha_inicial' => $parametros['fecha_inicial'],'fecha_final' => $parametros['fecha_final'], 'por_dia' => $parametros['por_dia']]);
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
                'mimes'    => 'El :attribute debe ser de tipo .txt',
                'numeric'  => 'El :attribute debe ser numérico',
            ];

            $rules = [
                'tipo_envio'              => 'required'
            ];

            if(isset($request->tipo_envio) && $request->tipo_envio==1){ // desde inputs
                $rules['temperatura'] = 'required|numeric';
            } else { // desde archivo txt
                $rules['archivo'] = 'required|mimes:txt';
            }
            
            $this->validate($request, $rules, $messages);
            try {
            if(isset($request->tipo_envio) && $request->tipo_envio==1){                                
                $clue = Clue::find(4184); 
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
                $temperatura->contenedores_id    = 1;
                $temperatura->fecha              = Carbon::now("America/Mexico_City")->format('Y-m-d');
                $temperatura->hora               = Carbon::now("America/Mexico_City")->format('H:i:s');
                $temperatura->temperatura        = $request->temperatura;
                $temperatura->observacion        = 'Guardado desde formulario';
                $temperatura->usuario_id         = Auth::user()->email;
                $temperatura->created_at         = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
                $temperatura->save();

                $msgGeneral = 'Hey! se guardaron los datos';
                $type       = 'flash_message_ok';                
            } else {
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
                               
                $clue = Clue::find(4184);  
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
                    $temperatura->contenedores_id    = 1;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('Come on!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd('Come on!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd('Come on!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        dd('Come on!');
    }
}
