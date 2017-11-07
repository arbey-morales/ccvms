<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
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
        $parametros = Input::only('q');
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|admin')){
            $data = DB::table('poblacion_objetivo_conapo as poc')
            ->select('poc.*','m.clave as mun_clave','m.nombre as mun_nombre')
            ->leftJoin('municipios AS m','m.id','=','poc.municipios_id')
            ->where('poc.anio', Carbon::now()->format('Y'))
            ->where('poc.deleted_at', NULL)
            ->orderBy('poc.municipios_id', 'ASC');
            if ($parametros['q']) {
                $data = $data->where('poc.anio','LIKE',"%".$parametros['q']."%");
            }            
            $data = $data->get();
            /***
             * Muestra u oculta botón agregar población de año actual
             */
            $nuevo = 1;
            $data_municipios = Municipio::where('deleted_at', NULL)->count();
            $actual = PoblacionConapo::where('anio', Carbon::now()->format('Y'))->where('deleted_at', NULL)->count();
            if($actual>=$data_municipios)
                $nuevo = 0;
            /***
             * Muestra u oculta botón agregar población de año actual
             */

            if ($request->ajax())
                return response()->json([ 'data' => $data ]);
            else              
                return view('catalogo.poblacion-conapo.index')->with([ 'data' => $data, 'nuevo' => $nuevo ]);
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
