<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session; 
use App\Catalogo\Clue;
use App\Catalogo\Municipio;
use App\Catalogo\Institucion;
use App\Catalogo\Localidad;
use App\Catalogo\Jurisdiccion;
use App\Catalogo\Tipologia;

class ClueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = Input::only('q');
        
        if (Auth::user()->is('root|admin')) {
            if ($parametros['q']) {
                $data =  Clue::where('clues','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->with('municipio','localidad','jurisdiccion')->where('deleted_at',NULL)->get();
            } else {
                $data =  Clue::with('municipio','localidad','jurisdiccion')->where('deleted_at',NULL)->get();
            }
        } else {
            if ($parametros['q']) {
                $data = Clue::where('clues','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->with('municipio','localidad','jurisdiccion')->get();
            } else {
                $data = Clue::where('jurisdicciones_id', Auth::user()->idJurisdiccion)->where('deleted_at',NULL)->with('municipio','localidad','jurisdiccion')->get();
            }
        }       
        return view('catalogo.clue.index')->with('clues', $data)->with('q', $parametros['q']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
    {
        if (Auth::user()->can('create.catalogos') && Auth::user()->activo==1) {  
            $municipios = Municipio::where('deleted_at',NULL)->get();
            $jurisdicciones = Jurisdiccion::where('deleted_at',NULL)->get();
            $localidades = Localidad::where('deleted_at',NULL)->get();
			$instituciones = Institucion::where('deleted_at',NULL)->get();
			$tipologias = Tipologia::where('borradoAl',NULL)->get();

			foreach ($municipios as $municipio) {
                $arraymunicipio[$municipio->id] = $municipio->clave.' - '.$municipio->nombre;
            }
            foreach ($jurisdicciones as $jurisdiccion) {
                $arrayjurisdiccion[$jurisdiccion->id] = $jurisdiccion->clave.' - '.$jurisdiccion->nombre;
            }
            foreach ($localidades as $localidad) {
                $arraylocalidad[$localidad->id] = $localidad->clave.' - '.$localidad->nombre;
            }		
			foreach ($tipologias as $tipologia) {
                $arraytipologia[$tipologia->id] = $tipologia->clave.' - '.$tipologia->tipo.' - '.$tipologia->descripcion.' - '.$tipologia->nombre;
            }
			foreach ($instituciones as $institucion) {
                $arrayinstitucion[$institucion->id] = $institucion->clave .' - '.$institucion->nombre;
            }  
            return view('catalogo.clue.create')->with(['instituciones' => $arrayinstitucion, 'localidades' => $arraylocalidad, 'jurisdicciones' => $arrayjurisdiccion, 'municipios' => $arraymunicipio, 'tipologias' => $arraytipologia ]);
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
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha'
            ];

            $rules = [
                'pedidos_estatales_id'     => 'required|min:1|numeric',
                'descripcion'              => 'required|min:10|string',
            ];
            
            $this->validate($request, $rules, $messages);

            $cuadros = CuadroDistribucionJurisdiccional::count();

            $cuadro_distribucion_jurisdiccional = new CuadroDistribucionJurisdiccional;
            $cuadro_distribucion_jurisdiccional->folio                = 'CDJ-'.substr(date('m-d-Y'), 8, 2).''.substr(date('m-d-Y'), 0, 2).''.($cuadros+1);
            $cuadro_distribucion_jurisdiccional->pedidos_estatales_id = $request->pedidos_estatales_id;
            $cuadro_distribucion_jurisdiccional->fecha                = date("Y-m-d H:m:s");
            $cuadro_distribucion_jurisdiccional->descripcion          = $request->descripcion;            
            $cuadro_distribucion_jurisdiccional->usuario_id           = Auth::user()->email;
            $cuadro_distribucion_jurisdiccional->created_at           = date('Y-m-d H:m:s');

            //$cuadro_distribucion_jurisdiccional->save();
            $save_cdj = true;
            $msg_cdj = '';

            foreach($vacunas as $key_vac=>$value_vac){
                foreach($jurisdicciones as $key_jur=>$value_jur){
                    if(is_numeric($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-cantidad']) && $request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-cantidad']>=0){
                        if(isset($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-fecha-caducidad']) && !empty($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-fecha-caducidad'])){
                            $date_cad = explode("-", $request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-fecha-caducidad']);
                            if(!checkdate($date_cad[1], $date_cad[0], $date_cad[2]) || empty($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-fecha-caducidad'])){
                                $msg_cdj.= $value_vac->clave.':'.$value_jur->clave.' - Formato de fecha no válido';
                                $save_cdj = false; 
                                break; 
                            }
                        } else {
                            $msg_cdj.= $value_vac->clave.':'.$value_jur->clave.' - Debe selecionar una fecha de caducidad';
                            $save_cdj = false; 
                            break; 
                        }
                    } else {
                        $msg_cdj.= $value_vac->clave.':'.$value_jur->clave.' - Sólo números. Mayores o iguales a 0.';
                        $save_cdj = false; 
                        break;  
                    }     
                }        
            }
            
           if($save_cdj==true) {
                try {       
                    DB::beginTransaction();             
                    if($cuadro_distribucion_jurisdiccional->save()) {
                        $success = true;
                        foreach($vacunas as $key_vac=>$value_vac){
                            foreach($jurisdicciones as $key_jur=>$value_jur){
                                if(isset($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-cantidad'])){  
                                    $detalles_cuadro_distribucion_jurisdiccional = new DetalleCuadroDistribucionJurisdiccional;
                                    $detalles_cuadro_distribucion_jurisdiccional->cuadro_distribucion_jurisdiccionales_id = $cuadro_distribucion_jurisdiccional->id;
                                    $detalles_cuadro_distribucion_jurisdiccional->insumos_id                              = $value_vac->insumos_clave;
                                    $detalles_cuadro_distribucion_jurisdiccional->cantidad                                = $request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-cantidad'];
                                    $detalles_cuadro_distribucion_jurisdiccional->lote                                    = $request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-lote'];
                                    $detalles_cuadro_distribucion_jurisdiccional->fecha_caducidad                         =  date(substr($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-fecha-caducidad'], 6, 4).'-'.substr($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-fecha-caducidad'], 3, 2).'-'.substr($request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-fecha-caducidad'], 0, 2));
                                    $detalles_cuadro_distribucion_jurisdiccional->pt                                      = $request['vacuna-'.$value_vac->id.'-jurisdiccion-'.$value_jur->id.'-pt'];
                                    $detalles_cuadro_distribucion_jurisdiccional->fecha                                   = date('Y-m-d H:m:s');
                                    $detalles_cuadro_distribucion_jurisdiccional->usuario_id                              = Auth::user()->email;
                                    $detalles_cuadro_distribucion_jurisdiccional->created_at                              = date('Y-m-d H:m:s');
                                    if(!$detalles_cuadro_distribucion_jurisdiccional->save()){
                                        $success = false;
                                    }                                                                           
                                }
                            }
                        }
                        
                        if($success){
                            DB::commit();
                            $msgGeneral = 'Perfecto! se gurdaron los datos';
                            $type       = 'flash_message_ok';
                            Session::flash($type, $msgGeneral);
                            return redirect()->back();
                        } else {
                            DB::rollback();
                            $msgGeneral = 'No se guardaron los datos. Verifique su información o recargue la página.';
                            $type       = 'flash_message_error';
                        }
                    } else {
                        DB::rollback();
                        $msgGeneral = 'No se guardaron los datos personales. Verifique su información o recargue la página.';
                        $type       = 'flash_message_error';                            
                    }
                } catch(\PDOException $e){
                    $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados. Recargue la página e intente de nuevo';
                    $type       = 'flash_message_error';
                }   
            } else {
                $msgGeneral = $msg_cdj;
                $type       = 'flash_message_error';
            }        
            
            Session::flash($type, $msgGeneral);
            return redirect()->back()->withInput();

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
        $data = Clue::with('municipio','localidad','jurisdiccion')->find($id);        
        
        if(!$data ){            
            //return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
        }

       // return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);
        return response()->json([ 'data' => $data]);
    }
}
