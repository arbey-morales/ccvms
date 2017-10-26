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
use App\Catalogo\TipoUnidad;
use App\Catalogo\Estatus;
use App\Catalogo\Servidor;

class ClueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = Input::only('q','municipios_id');
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            $data = Clue::with('municipio','localidad','jurisdiccion')/*->where('instituciones_id',13)*/->where('deleted_at',NULL);
            if (Auth::user()->is('root|admin')) { } else {
                $data = $data->where('jurisdicciones_id', Auth::user()->idJurisdiccion);                
            }            
            if ($parametros['q']) {
                $data = $data->where('clues','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%");
            } 
            if ($parametros['municipios_id']) {
                $data = $data->where('municipios_id', $parametros['municipios_id']);
            }
            $data = $data->get();

            if ($request->ajax()) {
                return response()->json([ 'data' => $data]);
            } else {  
                return view('catalogo.clue.index')->with('data', $data)->with('q', $parametros['q']);
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
        if (Auth::user()->can('create.catalogos') && Auth::user()->is('root|admin') && Auth::user()->activo==1) {  
            $municipio_selected = 101;
            $municipios = Municipio::where('deleted_at',NULL)->get();
            $jurisdicciones = Jurisdiccion::where('deleted_at',NULL)->get();
			$instituciones = Institucion::where('deleted_at',NULL)->get();
            $tipologias = Tipologia::where('deleted_at',NULL)->get();
            $estatuss = Estatus::where('deleted_at',NULL)->get();
            $tiposunidad = TipoUnidad::where('deleted_at',NULL)->get();

            foreach ($tiposunidad as $tipounidad) {
                $arraytiposunidad[$tipounidad->id] = $tipounidad->clave.' - '.$tipounidad->nombre;
            }
			foreach ($municipios as $municipio) {
                $arraymunicipio[$municipio->id] = $municipio->clave.' - '.$municipio->nombre;
            }
            foreach ($jurisdicciones as $jurisdiccion) {
                $arrayjurisdiccion[$jurisdiccion->id] = $jurisdiccion->clave.' - '.$jurisdiccion->nombre;
            }	
			foreach ($tipologias as $tipologia) {
                $arraytipologia[$tipologia->id] = $tipologia->clave.' - '.$tipologia->tipo.' - '.$tipologia->descripcion.' - '.$tipologia->nombre;
            }
            foreach ($estatuss as $estatus) {
                $arrayestatus[$estatus->id] = $estatus->clave.' - '.$estatus->descripcion;
            }
			foreach ($instituciones as $institucion) {
                $arrayinstitucion[$institucion->id] = $institucion->clave .' - '.$institucion->nombre;
            }  
            return view('catalogo.clue.create')->with(['municipio_selected' => $municipio_selected, 'instituciones' => $arrayinstitucion, 'tipos_unidades' => $arraytiposunidad, 'jurisdicciones' => $arrayjurisdiccion, 'municipios' => $arraymunicipio, 'tipologias' => $arraytipologia, 'estatus' => $arrayestatus ]);
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
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha'
            ];

            $rules = [
                'jurisdicciones_id'     => 'required|min:1|numeric',
                'municipios_id'         => 'required|min:1|numeric',
                'localidades_id'        => 'required|min:1|numeric',
                'instituciones_id'      => 'required|min:1|numeric',
                'tipologias_id'         => 'required|min:1|numeric',
                'estatus_id'            => 'required|min:1|numeric',
                'tipos_unidades_id'     => 'required|min:1|numeric',
                'clues'                 => 'required|min:5|max:11 |string',
                'nombre'                => 'required|min:3|max:100|string',
                'domicilio'             => 'required|min:5|max:200|string',
                'codigo_postal'         => 'required|min:5|max:5|string'                
            ];
            
            $this->validate($request, $rules, $messages);

            $clues = Clue::count();

            $servidor_id = (str_pad(($clues+1), 4, "0", STR_PAD_LEFT));

            $clue = new Clue;
            $clue->jurisdicciones_id            = $request->jurisdicciones_id;
            $clue->municipios_id                = $request->municipios_id;
            $clue->localidades_id               = $request->localidades_id;
            $clue->instituciones_id             = $request->instituciones_id;
            $clue->tipologias_id                = $request->tipologias_id;
            $clue->estatus_id                   = $request->estatus_id;
            $clue->tipos_unidades_id            = $request->tipos_unidades_id;
            $clue->servidor                     = $servidor_id;
            $clue->clues                        = $request->clues;
            $clue->nombre                       = $request->nombre;            
            $clue->domicilio                    = $request->domicilio;
            $clue->codigo_postal                = $request->codigo_postal;
            $clue->numero_latitud               = $request->numero_latitud;
            $clue->numero_longitud              = $request->numero_longitud;
            $clue->consultorios                 = $request->consultorios;
            $clue->camas                        = $request->camas;
            $clue->fecha_construccion           = $request->fecha_construccion;
            $clue->fecha_inicio_operacion       = $request->fecha_inicio_operacion;
            $clue->telefono1                    = $request->telefono1;
            $clue->telefono2                    = $request->telefono2;
            $clue->created_at                   = date('Y-m-d H:m:s');
            
            try {
                DB::beginTransaction();
                if($clue->save()) {
                    $servidor = new Servidor;
                    $servidor->id                        = $servidor_id;
                    $servidor->nombre                    = 'Servidor CLUE: '.$request->clues;
                    $servidor->secret_key                = md5(microtime().rand());
                    $servidor->created_at                = date('Y-m-d H:m:s');
                    $servidor->save();
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
                $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados. Recargue la página e intente de nuevo'.$e->getMessage();
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
     {
         if (Auth::user()->is('admin|root') && Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            $data = Clue::findOrFail($id);
            if($data) {                
                $municipio_selected = $data->municipios_id;
                $municipios = Municipio::where('deleted_at',NULL)->get();
                $jurisdicciones = Jurisdiccion::where('deleted_at',NULL)->get();
                $instituciones = Institucion::where('deleted_at',NULL)->get();
                $tipologias = Tipologia::where('deleted_at',NULL)->get();
                $estatuss = Estatus::where('deleted_at',NULL)->get();
                $tiposunidad = TipoUnidad::where('deleted_at',NULL)->get();
    
                foreach ($tiposunidad as $tipounidad) {
                    $arraytiposunidad[$tipounidad->id] = $tipounidad->clave.' - '.$tipounidad->nombre;
                }
                foreach ($municipios as $municipio) {
                    $arraymunicipio[$municipio->id] = $municipio->clave.' - '.$municipio->nombre;
                }
                foreach ($jurisdicciones as $jurisdiccion) {
                    $arrayjurisdiccion[$jurisdiccion->id] = $jurisdiccion->clave.' - '.$jurisdiccion->nombre;
                }	
                foreach ($tipologias as $tipologia) {
                    $arraytipologia[$tipologia->id] = $tipologia->clave.' - '.$tipologia->tipo.' - '.$tipologia->descripcion.' - '.$tipologia->nombre;
                }
                foreach ($estatuss as $estatus) {
                    $arrayestatus[$estatus->id] = $estatus->clave.' - '.$estatus->descripcion;
                }
                foreach ($instituciones as $institucion) {
                    $arrayinstitucion[$institucion->id] = $institucion->clave .' - '.$institucion->nombre;
                }  
                return view('catalogo.clue.edit')->with(['data' => $data,'municipio_selected' => $municipio_selected, 'instituciones' => $arrayinstitucion, 'tipos_unidades' => $arraytiposunidad, 'jurisdicciones' => $arrayjurisdiccion, 'municipios' => $arraymunicipio, 'tipologias' => $arraytipologia, 'estatus' => $arrayestatus ]);
            } else {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
         } else {
             return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
         }
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
        $msgGeneral = '';
        $type       = 'flash_message_info';

        if (Auth::user()->is('root|admin') && Auth::user()->can('update.catalogos') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha'
            ];

            $rules = [
                'jurisdicciones_id'     => 'required|min:1|numeric',
                'municipios_id'         => 'required|min:1|numeric',
                'localidades_id'        => 'required|min:1|numeric',
                'instituciones_id'      => 'required|min:1|numeric',
                'tipologias_id'         => 'required|min:1|numeric',
                'estatus_id'            => 'required|min:1|numeric',
                'tipos_unidades_id'     => 'required|min:1|numeric',
                'clues'                 => 'required|min:5|max:11 |string',
                'nombre'                => 'required|min:3|max:100|string',
                'domicilio'             => 'required|min:5|max:200|string',
                'codigo_postal'         => 'required|min:5|max:5|string'                
            ];
            
            $this->validate($request, $rules, $messages);

            $clue = Clue::find($id);
            
            $clue->jurisdicciones_id            = $request->jurisdicciones_id;
            $clue->municipios_id                = $request->municipios_id;
            $clue->localidades_id               = $request->localidades_id;
            $clue->instituciones_id             = $request->instituciones_id;
            $clue->tipologias_id                = $request->tipologias_id;
            $clue->estatus_id                   = $request->estatus_id;
            $clue->tipos_unidades_id            = $request->tipos_unidades_id;
            $clue->clues                        = $request->clues;
            $clue->nombre                       = $request->nombre;            
            $clue->domicilio                    = $request->domicilio;
            $clue->codigo_postal                = $request->codigo_postal;
            $clue->numero_latitud               = $request->numero_latitud;
            $clue->numero_longitud              = $request->numero_longitud;
            $clue->consultorios                 = $request->consultorios;
            $clue->camas                        = $request->camas;
            $clue->fecha_construccion           = $request->fecha_construccion;
            $clue->fecha_inicio_operacion       = $request->fecha_inicio_operacion;
            $clue->telefono1                    = $request->telefono1;
            $clue->telefono2                    = $request->telefono2;
            $clue->updated_at                   = date('Y-m-d H:m:s');
            
            try {
                DB::beginTransaction();
                if($clue->save()) {
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
                $msgGeneral = 'Ocurrió un error al intentar guardar los datos enviados. Recargue la página e intente de nuevo'.$e->getMessage();
                $type       = 'flash_message_error';
            }        
            
            Session::flash($type, $msgGeneral);
            return redirect()->back();

        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id, Request $request)
    {
        $msgGeneral     = '';
        $type           = 'flash_message_info';
        $type2          = 'error';        
        $clue = Clue::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->is('root|admin') && Auth::user()->can('delete.catalogos') && Auth::user()->activo==1) {
                try {                    
                    DB::beginTransaction();
                    $updates = DB::table('clues')
                            ->where('id', '=', $id)
                            ->update(['deleted_at' => date('Y-m-d H:m:s')]);
                    if ($updates>=0) {
                        DB::commit();
                        $msgGeneral = 'Se borraron los datos';
                        $type2      = 'success';
                    } else {
                        DB::rollback();
                        $msgGeneral = 'NO se borraron los datos';
                        $type2      = 'error';
                    }
                } catch(\PDOException $e){
                    $msgGeneral = 'Ocurrió un error al intentar eliminar los datos.';
                    $type2      = 'error';
                }
            } else {
                $msgGeneral = 'No tiene autorización para acceder al recurso. Se ha negado el acceso.';
                $type2      = 'error';
            }

            return response()->json([
                'code'    => 1,
                'title'   => 'Información',
                'text'    => $msgGeneral,
                'type'    => $type2,
                'styling' => 'bootstrap3'
            ]);
        } else {
            Session::flash('flash_message_error', 'No submit!');
            return redirect()->back();
        }
    }
}
