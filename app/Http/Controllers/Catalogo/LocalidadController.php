<?php

namespace App\Http\Controllers\Catalogo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session;
use App\Catalogo\Municipio;
use App\Catalogo\Localidad;

class LocalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            $parametros = Input::only('q','municipios_id');
            $data =  DB::table('localidades as l')
                ->select('l.*','m.nombre as municipio')
                ->leftJoin('municipios as m','m.id','=','l.municipios_id')
                ->where('l.deleted_at',NULL)
                ->orderBy('l.nombre', 'ASC');
            if (Auth::user()->is('root|admin')) { } else {
                $data = $data->where('m.jurisdicciones_id', Auth::user()->idJurisdiccion);
            }  
            if ($parametros['q']) {
                $data = $data->where('l.clave','LIKE',"%".$parametros['q']."%")->orWhere('l.nombre','LIKE',"%".$parametros['q']."%");
            }
            if ($parametros['municipios_id']) {
                $data = $data->where('l.municipios_id', $parametros['municipios_id']);
            }

            $data = $data->orderBy('municipio', 'ASC')->orderBy('l.nombre', 'ASC')->get();
            if ($request->ajax()) {
                return response()->json([ 'data' => $data]);
            } else {       
                return view('catalogo.localidad.index')->with('localidades', $data);  
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
			foreach ($municipios as $municipio) {
                $arraymunicipio[$municipio->id] = $municipio->clave.' - '.$municipio->nombre;
            }   
            return view('catalogo.localidad.create')->with(['municipios' => $arraymunicipio]);
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
                 'clave'                 => 'required|min:1|unique:localidades,clave,NULL,id,deleted_at,NULL',
                 'nombre'                => 'required|min:3|max:100|string'                
             ];
             
             $this->validate($request, $rules, $messages);

             $municipio = Municipio::find($request->municipios_id);
 
             $localidad = new Localidad;
             $localidad->municipios_id                = $request->municipios_id;
             $localidad->municipios_clave             = $municipio->clave;
             $localidad->clave_carta                  = $request->clave_carta;
             $localidad->numero_latitud               = $request->numero_latitud;
             $localidad->entidades_id                 = 7;
             $localidad->numero_longitud              = $request->numero_longitud;
             $localidad->numero_altitud               = $request->numero_altitud;
             $localidad->nombre                       = $request->nombre;  
             $localidad->clave                        = $request->clave;
             $localidad->usuario_id                   = Auth::user()->email;
             $localidad->created_at                   = date('Y-m-d H:m:s');
             
             try {
                 DB::beginTransaction();
                 if($localidad->save()) {                    
                     DB::commit();
                     $msgGeneral = 'Perfecto! se guardaron los datos';
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
        $data = Localidad::with('municipio')->find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
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
            $data = Localidad::findOrFail($id);
            if($data) {                
                $municipios = Municipio::where('deleted_at',NULL)->get();
                foreach ($municipios as $municipio) {
                    $arraymunicipio[$municipio->id] = $municipio->clave.' - '.$municipio->nombre;
                }               
                return view('catalogo.localidad.edit')->with(['data' => $data,'municipios' => $arraymunicipio]); 
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
                'clave'                 => 'required|min:1|unique:localidades,clave,'.$id.',id,deleted_at,NULL',
                'nombre'                => 'required|min:3|max:100|string'                
            ];
            
            $this->validate($request, $rules, $messages);

            $municipio = Municipio::find($request->municipios_id);
            
            $localidad                               = Localidad::find($id);
            $localidad->municipios_id                = $request->municipios_id;
            $localidad->municipios_clave             = $municipio->clave;
            $localidad->clave_carta                  = $request->clave_carta;
            $localidad->numero_latitud               = $request->numero_latitud;
            $localidad->entidades_id                 = 7;
            $localidad->numero_longitud              = $request->numero_longitud;
            $localidad->numero_altitud               = $request->numero_altitud;
            $localidad->nombre                       = $request->nombre;  
            $localidad->clave                        = $request->clave;
            $localidad->usuario_id                   = Auth::user()->email;
            $localidad->updated_at                   = date('Y-m-d H:m:s');
            
            try {
                DB::beginTransaction();
                if($localidad->save()) {                    
                    DB::commit();
                    $msgGeneral = 'Perfecto! se guardaron los datos';
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
        $localidad = Localidad::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->is('root|admin') && Auth::user()->can('delete.catalogos') && Auth::user()->activo==1) {
                try {                    
                    DB::beginTransaction();
                    $updates = DB::table('localidades')
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
