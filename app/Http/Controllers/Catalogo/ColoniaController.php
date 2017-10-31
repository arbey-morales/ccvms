<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session; 
use App\Catalogo\Colonia;
use App\Catalogo\Municipio;
use App\Catalogo\TipoZona;
use App\Catalogo\TipoAsentamiento;
use App\Catalogo\Ciudad;

class ColoniaController extends Controller
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
            $data =  DB::table('colonias as c')->select('c.*','m.nombre as mun_nombre','ci.descripcion as ciu_nombre')                
                ->leftJoin('municipios as m','m.id','=','c.municipios_id')
                ->leftJoin('ciudades as ci','ci.id','=','c.ciudades_id')
                ->leftJoin('jurisdicciones as j','j.id','=','m.jurisdicciones_id')
                ->where('c.deleted_at',NULL)
                ->orderBy('c.nombre', 'ASC');

            if (Auth::user()->is('root|admin')) { } else {
                $data = $data->where('m.jurisdicciones_id',Auth::user()->idJurisdiccion);
            }

            if ($parametros['q']) {
                $data = $data->where('c.codigo_postal','LIKE',"%".$parametros['q']."%")->orWhere('c.nombre','LIKE',"%".$parametros['q']."%");
            }
            if ($parametros['municipios_id']) {
                $data = $data->where('municipios_id', $parametros['municipios_id']);
            }

            $data = $data->get();

            if ($request->ajax()) {
                return response()->json([ 'data' => $data]);
            } else { 
                return view('catalogo.colonia.index')->with('data', $data)->with('q', $parametros['q']);
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
            $municipios = Municipio::where('deleted_at',NULL)->get();
            $asentamientos = TipoAsentamiento::where('deleted_at',NULL)->get();
            $zonas = TipoZona::where('deleted_at',NULL)->get();
            $ciudades = Ciudad::where('deleted_at',NULL)->get();
			foreach ($municipios as $municipio) {
                $arraymunicipio[$municipio->id] = $municipio->clave.' - '.$municipio->nombre;
            }
            foreach ($asentamientos as $asentamiento) {
                $arrayasentamiento[$asentamiento->id] = $asentamiento->descripcion;
            }
            foreach ($zonas as $zona) {
                $arrayzona[$zona->id] = $zona->descripcion;
            }
            foreach ($ciudades as $ciudad) {
                $arrayciudad[$ciudad->id] = $ciudad->descripcion;
            }
           
            return view('catalogo.colonia.create')->with(['municipios' => $arraymunicipio, 'zonas' => $arrayzona, 'asentamientos' => $arrayasentamiento, 'ciudades' => $arrayciudad]);
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
                'municipios_id'         => 'required|min:1|numeric',
                'ciudades_id'           => 'required|min:1|numeric',
                'tipos_zona_id'         => 'required|min:1|numeric',
                'tipos_asentamiento_id' => 'required|min:1|numeric',
                'nombre'                => 'required|min:3|max:100|string',
                'codigo_postal'         => 'required|min:5|max:5|string'                
            ];
            
            $this->validate($request, $rules, $messages);

            $colonia = new Colonia;
            $colonia->municipios_id                = $request->municipios_id;
            $colonia->ciudades_id                  = $request->ciudades_id;
            $colonia->entidades_id                 = 7;
            $colonia->tipos_zona_id                = $request->tipos_zona_id;
            $colonia->tipos_asentamiento_id        = $request->tipos_asentamiento_id;
            $colonia->nombre                       = $request->nombre;  
            $colonia->codigo_postal                = $request->codigo_postal;
            $colonia->created_at                   = date('Y-m-d H:m:s');
            
            try {
                DB::beginTransaction();
                if($colonia->save()) {                    
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
        $data = Colonia::with('municipio','entidad','ciudad')->find($id);
        if(!$data ){            
            return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
        }
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
            $data = Colonia::findOrFail($id);
            if($data) {                
                $municipios = Municipio::where('deleted_at',NULL)->get();
                $asentamientos = TipoAsentamiento::where('deleted_at',NULL)->get();
                $zonas = TipoZona::where('deleted_at',NULL)->get();
                $ciudades = Ciudad::where('deleted_at',NULL)->get();
                foreach ($municipios as $municipio) {
                    $arraymunicipio[$municipio->id] = $municipio->clave.' - '.$municipio->nombre;
                }
                foreach ($asentamientos as $asentamiento) {
                    $arrayasentamiento[$asentamiento->id] = $asentamiento->descripcion;
                }
                foreach ($zonas as $zona) {
                    $arrayzona[$zona->id] = $zona->descripcion;
                }
                foreach ($ciudades as $ciudad) {
                    $arrayciudad[$ciudad->id] = $ciudad->descripcion;
                }
               
                return view('catalogo.colonia.edit')->with(['data' => $data,'municipios' => $arraymunicipio, 'zonas' => $arrayzona, 'asentamientos' => $arrayasentamiento, 'ciudades' => $arrayciudad]); 
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
                'municipios_id'         => 'required|min:1|numeric',
                'ciudades_id'           => 'required|min:1|numeric',
                'tipos_zona_id'         => 'required|min:1|numeric',
                'tipos_asentamiento_id' => 'required|min:1|numeric',
                'nombre'                => 'required|min:3|max:100|string',
                'codigo_postal'         => 'required|min:5|max:5|string'                
            ];
            
            $this->validate($request, $rules, $messages);

            $colonia = Colonia::find($id);
            $colonia->municipios_id                = $request->municipios_id;
            $colonia->ciudades_id                  = $request->ciudades_id;
            $colonia->tipos_zona_id                = $request->tipos_zona_id;
            $colonia->tipos_asentamiento_id        = $request->tipos_asentamiento_id;
            $colonia->nombre                       = $request->nombre;  
            $colonia->codigo_postal                = $request->codigo_postal;
            $colonia->updated_at                   = date('Y-m-d H:m:s');
            
            try {
                DB::beginTransaction();
                if($colonia->save()) {                    
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
        $colonia = Colonia::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->is('root|admin') && Auth::user()->can('delete.catalogos') && Auth::user()->activo==1) {
                try {                    
                    DB::beginTransaction();
                    $updates = DB::table('colonias')
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
