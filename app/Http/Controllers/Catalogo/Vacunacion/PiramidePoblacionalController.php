<?php

namespace App\Http\Controllers\Catalogo\Vacunacion;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, DB, Input, Excel;
use Carbon\Carbon;

use Session; 
use App\Models\Catalogo\Vacunacion\PiramidePoblacional;
use App\Catalogo\Clue;

class PiramidePoblacionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = Input::only('q','anio');
        $anio = Carbon::now()->format('Y');
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|admin')){
            $data = DB::table('piramide_poblacional as pp')
            ->select('pp.*','c.clues as clues','c.nombre as clue_nombre')
            ->leftJoin('clues AS c','c.id','=','pp.clues_id')            
            ->where('pp.deleted_at', NULL)
            ->orderBy('pp.clues_id', 'ASC');
            if ($parametros['q']) {
                $data = $data->where('pp.anio','LIKE',"%".$parametros['q']."%");
            }      
            if ($parametros['anio']) {
                $data = $data->where('pp.anio', $parametros['anio']);
                $anio = $parametros['anio'];
            } else {
                $data = $data->where('pp.anio', Carbon::now()->format('Y'));
            }     
            $data = $data->get();

            if ($request->ajax())
                return response()->json([ 'data' => $data, 'anio' => $anio ]);
            else              
                return view('catalogo.vacunacion.piramide-poblacional.index')->with([ 'data' => $data, 'anio' => $anio ]);
        } else {
            if ($request->ajax())
                return response()->json([ 'mensaje' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.']);
            else              
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
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|admin|captura')){
            return view('catalogo.vacunacion.piramide-poblacional.create');
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

        if (Auth::user()->is('root|admin|captura') && Auth::user()->can('create.catalogos') && Auth::user()->activo==1) {
            try {
                DB::beginTransaction();
                $all = true;
                $clue = Clue::find($request->clues_id);
                $pp = PiramidePoblacional::where('anio', $request->anio)->where('clues_id',$request->clues_id)->where('deleted_at', NULL)->get();

                if(count($pp)>0){
                    $piramide = PiramidePoblacional::find($pp[0]->id);
                } else {
                    $piramide = new PiramidePoblacional;
                }
                
                $piramide->anio                   = $request->anio;
                $piramide->clues_id               = $clue->id;
                $piramide->hombres_0              = $request->hombres_0;
                $piramide->hombres_1              = $request->hombres_1;
                $piramide->hombres_2              = $request->hombres_2;
                $piramide->hombres_3              = $request->hombres_3;
                $piramide->hombres_4              = $request->hombres_4;
                $piramide->hombres_5              = $request->hombres_5;
                $piramide->hombres_6              = $request->hombres_6;
                $piramide->hombres_7              = $request->hombres_7;
                $piramide->hombres_8              = $request->hombres_8;
                $piramide->hombres_9              = $request->hombres_9;
                $piramide->hombres_10             = $request->hombres_10;
                $piramide->mujeres_0              = $request->mujeres_0;
                $piramide->mujeres_1              = $request->mujeres_1;
                $piramide->mujeres_2              = $request->mujeres_2;
                $piramide->mujeres_3              = $request->mujeres_3;
                $piramide->mujeres_4              = $request->mujeres_4;
                $piramide->mujeres_5              = $request->mujeres_5;
                $piramide->mujeres_6              = $request->mujeres_6;
                $piramide->mujeres_7              = $request->mujeres_7;
                $piramide->mujeres_8              = $request->mujeres_8;
                $piramide->mujeres_9              = $request->mujeres_9;
                $piramide->mujeres_10             = $request->mujeres_10;
                $piramide->usuario_id             = Auth::user()->email;
                $piramide->created_at             = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');

                    
                if($piramide->save()) {                    
                    DB::commit();
                    $msgGeneral = 'Perfecto! se guardaron los datos';
                    $type       = 'flash_message_ok';
                    Session::flash($type, $msgGeneral);
                    return redirect()->back();
                } else {
                    DB::rollback();
                    $msgGeneral = 'No se guardaron los datos. Verifique su información o recargue la página.';
                    $type       = 'flash_message_error';                            
                }
            } catch(\PDOException $e){
                $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados. Recargue la página e intente de nuevo: '.json_encode($request['hombresdiez46']).' ---- '.$e->getMessage();
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
      public function clueDetalle(Request $request)
      {
          $data = PiramidePoblacional::where('clues_id', $request->clues_id)->where('anio', $request->anio)->get();
          if(!$data ){            
              return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
          }
          return response()->json([ 'data' => $data]);
      }

    /**
     * Importa población CONAPO desde archivo de excel.
     *
     * @param  int  $archivo
     * @param  int  $anio
     * @return \Illuminate\Http\Response
     */
    public function importar()
    {
        $msgGeneral = '';
        $type       = 'flash_message_info';
        $success = false;
        
        if(Input::hasFile('archivo')){ 
            
         // DB::beginTransaction();             
            $path = Input::file('archivo')->getRealPath();  
            try {         
                Excel::load($path, function($reader) {
                    $parametros = Input::only('anio');
                    $results = $reader->get(); 
                    $municipios = Municipio::select('id','clave','deleted_at')->where('deleted_at', NULL)->orderBy('clave', 'ASC')->get();  
                    if(!empty($results) && $results->count()){
                        foreach ($results[0] as $key => $value) { // Hoja 1 - Hombres
                            foreach ($municipios as $keym => $valuem) {
                                $poblacion_conapo = PiramidePoblacional::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if(count($poblacion_conapo)===0)
                                    DB::table('piramide_poblacional')->insert(['anio' => $parametros['anio'], 'municipios_id' => $valuem->id, 'usuario_id'=>Auth::user()->email]);
                                $pob_conapo = PiramidePoblacional::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if (isset($value['7'.$valuem->clave])){                                
                                    if(trim($value['7'.$valuem->clave])!=NULL && trim($value['7'.$valuem->clave])!=""){
                                        DB::table('piramide_poblacional')->where('id','=',$pob_conapo[0]->id)->update(['hombres_'.$key=>$value['7'.$valuem->clave], 'usuario_id'=>Auth::user()->email]);
                                    }
                                }
                            }
                        }
                        
                        foreach ($results[1] as $key => $value) { // Hoja 2 - Mujeres
                            foreach ($municipios as $keym => $valuem) {
                                $poblacion_conapo = PiramidePoblacional::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if(count($poblacion_conapo)===0)
                                    DB::table('piramide_poblacional')->insert(['anio' => $parametros['anio'], 'municipios_id' => $valuem->id, 'usuario_id'=>Auth::user()->email]);
                                $pob_conapo = PiramidePoblacional::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if (isset($value['7'.$valuem->clave])){                                
                                    if(trim($value['7'.$valuem->clave])!=NULL && trim($value['7'.$valuem->clave])!=""){
                                        DB::table('piramide_poblacional')->where('id','=',$pob_conapo[0]->id)->update(['mujeres_'.$key=>$value['7'.$valuem->clave], 'usuario_id'=>Auth::user()->email]);
                                    }
                                }
                            }
                        }
                    }
                })->get();
                                    
                $msgGeneral = 'Perfecto! se guardaron los datos';
                $type       = 'flash_message_ok';
            } catch(\PDOException $e){
                $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados. Recargue la página e intente de nuevo';
                $type       = 'flash_message_error';
            }
        }
        Session::flash($type, $msgGeneral);
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PiramidePoblacional::find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);
    }
}
