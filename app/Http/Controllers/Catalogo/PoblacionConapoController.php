<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Excel;
use Carbon\Carbon;

use Session; 
use App\Catalogo\PoblacionConapo;
use App\Catalogo\Municipio;

class PoblacionConapoController extends Controller
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
            $data = DB::table('poblacion_objetivo_conapo as poc')
            ->select('poc.*','m.clave as mun_clave','m.nombre as mun_nombre')
            ->leftJoin('municipios AS m','m.id','=','poc.municipios_id')            
            ->where('poc.deleted_at', NULL)
            ->orderBy('poc.municipios_id', 'ASC');
            if ($parametros['q']) {
                $data = $data->where('poc.anio','LIKE',"%".$parametros['q']."%");
            }      
            if ($parametros['anio']) {
                $data = $data->where('poc.anio', $parametros['anio']);
                $anio = $parametros['anio'];
            } else {
                $data = $data->where('poc.anio', Carbon::now()->format('Y'));
            }     
            $data = $data->get();

            if ($request->ajax())
                return response()->json([ 'data' => $data, 'anio' => $anio ]);
            else              
                return view('catalogo.poblacion-conapo.index')->with([ 'data' => $data, 'anio' => $anio ]);
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
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|admin')){
            $data_original = Municipio::where('deleted_at', NULL)->orderBy('clave','asc')->get();
            $data = collect();
            $total = 0;
            foreach ($data_original as $key => $value) {
                $poc = PoblacionConapo::where('anio', Carbon::now()->format('Y'))->where('municipios_id',$value->id)->where('deleted_at', NULL)->count();
                if ($poc==0) {
                    $data->push($value);
                    $total++;
                }
                if($total>=10){
                    break;
                }
            }
            
            return view('catalogo.poblacion-conapo.create')->with(['data' => $data]);
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

        if (Auth::user()->is('root|admin') && Auth::user()->can('create.catalogos') && Auth::user()->activo==1) {
            try {
                DB::beginTransaction();
                $all = true;
                $data_original = Municipio::where('deleted_at', NULL)->orderBy('clave','asc')->get();
                $data = collect();
                $total = 0;
                foreach ($data_original as $key => $value) {
                    $poc = PoblacionConapo::where('anio', Carbon::now()->format('Y'))->where('municipios_id',$value->id)->where('deleted_at', NULL)->count();
                    if ($poc==0) {
                        $data->push($value);
                        $total++;
                    }
                    if($total>=10){
                        break;
                    }
                }
                foreach ($data as $key => $value) {
                    $poblacion = new PoblacionConapo;
                    $poblacion->anio                   = Carbon::now()->format('Y');
                    $poblacion->municipios_id          = $value->id;
                    $poblacion->hombres_0              = $request['hombrescero'.$value->id];
                    $poblacion->hombres_1              = $request['hombresuno'.$value->id];
                    $poblacion->hombres_2              = $request['hombresdos'.$value->id];
                    $poblacion->hombres_3              = $request['hombrestres'.$value->id];
                    $poblacion->hombres_4              = $request['hombrescuatro'.$value->id];
                    $poblacion->hombres_5              = $request['hombrescinco'.$value->id];
                    $poblacion->hombres_6              = $request['hombresseis'.$value->id];
                    $poblacion->hombres_7              = $request['hombressiete'.$value->id];
                    $poblacion->hombres_8              = $request['hombresocho'.$value->id];
                    $poblacion->hombres_9              = $request['hombresnueve'.$value->id];
                    $poblacion->hombres_10             = $request['hombresdiez'.$value->id];
                    $poblacion->mujeres_0              = $request['mujerescero'.$value->id];
                    $poblacion->mujeres_1              = $request['mujeresuno'.$value->id];
                    $poblacion->mujeres_2              = $request['mujeresdos'.$value->id];
                    $poblacion->mujeres_3              = $request['mujerestres'.$value->id];
                    $poblacion->mujeres_4              = $request['mujerescuatro'.$value->id];
                    $poblacion->mujeres_5              = $request['mujerescinco'.$value->id];
                    $poblacion->mujeres_6              = $request['mujeresseis'.$value->id];
                    $poblacion->mujeres_7              = $request['mujeressiete'.$value->id];
                    $poblacion->mujeres_8              = $request['mujeresocho'.$value->id];
                    $poblacion->mujeres_9              = $request['mujeresnueve'.$value->id];
                    $poblacion->mujeres_10             = $request['mujeresdiez'.$value->id];
                    $poblacion->usuario_id             = Auth::user()->email;
                    $poblacion->created_at             = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
                    if($poblacion->save()){

                    } else {
                        $all = false;
                        break;
                    }
                }
                    
                if($all) {                    
                    DB::commit();
                    $msgGeneral = 'Perfecto! se gurdaron los datos';
                    $type       = 'flash_message_ok';
                    Session::flash($type, $msgGeneral);
                    return redirect()->back();
                } else {
                    DB::rollback();
                    $msgGeneral = 'No se guardaron los datos personales. Verifique su información o recargue la página.';
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
                                $poblacion_conapo = PoblacionConapo::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if(count($poblacion_conapo)===0)
                                    DB::table('poblacion_objetivo_conapo')->insert(['anio' => $parametros['anio'], 'municipios_id' => $valuem->id, 'usuario_id'=>Auth::user()->email]);
                                $pob_conapo = PoblacionConapo::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if (isset($value['7'.$valuem->clave])){                                
                                    if(trim($value['7'.$valuem->clave])!=NULL && trim($value['7'.$valuem->clave])!=""){
                                        DB::table('poblacion_objetivo_conapo')->where('id','=',$pob_conapo[0]->id)->update(['hombres_'.$key=>$value['7'.$valuem->clave], 'usuario_id'=>Auth::user()->email]);
                                    }
                                }
                            }
                        }
                        
                        foreach ($results[1] as $key => $value) { // Hoja 2 - Mujeres
                            foreach ($municipios as $keym => $valuem) {
                                $poblacion_conapo = PoblacionConapo::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if(count($poblacion_conapo)===0)
                                    DB::table('poblacion_objetivo_conapo')->insert(['anio' => $parametros['anio'], 'municipios_id' => $valuem->id, 'usuario_id'=>Auth::user()->email]);
                                $pob_conapo = PoblacionConapo::where('deleted_at', NULL)->where('anio', $parametros['anio'])->where('municipios_id', $valuem->id)->take(1)->get();
                                if (isset($value['7'.$valuem->clave])){                                
                                    if(trim($value['7'.$valuem->clave])!=NULL && trim($value['7'.$valuem->clave])!=""){
                                        DB::table('poblacion_objetivo_conapo')->where('id','=',$pob_conapo[0]->id)->update(['mujeres_'.$key=>$value['7'.$valuem->clave], 'usuario_id'=>Auth::user()->email]);
                                    }
                                }
                            }
                        }
                    }
                })->get();
                                    
                $msgGeneral = 'Perfecto! se gurdaron los datos';
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
        $data = PoblacionConapo::find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);
    }
}
