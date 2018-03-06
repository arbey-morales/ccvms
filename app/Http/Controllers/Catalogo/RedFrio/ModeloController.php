<?php

namespace App\Http\Controllers\Catalogo\RedFrio;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Carbon\Carbon;
use Session; 

use App\Models\Catalogo\RedFrio\Modelo;
use App\Models\Catalogo\RedFrio\Marca;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|red-frio')) {    
            $data = DB::table('modelos AS mo')
                    ->select('mo.*','ma.id AS marca_id','ma.nombre AS marca_nombre')
                    ->leftJoin('marcas AS ma','ma.id','=','mo.marcas_id')
                    ->where('mo.deleted_at',NULL);
            if ($request['q']) {
                $data = $data->where('mo.nombre','LIKE',"%".$request['q']."%")
                             ->orWhere('ma.nombre','LIKE',"%".$request['q']."%");
            }  
            $data = $data->get(); 

            if ($request->ajax()) {
                return response()->json([ 'data' => $data]);
            } else {             
                return view('catalogo.modelo.index')->with(['data'=>$data,'q'=>$request['q']]);
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
        return view('catalogo.modelo.create');
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

        if (Auth::user()->is('root|red-frio') && Auth::user()->can('create.catalogos') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha'
            ];

            $rules = [
                'nombre'                => 'required|min:3|max:100|string|unique:modelos,nombre,NULL,id,deleted_at,NULL',
                'marcas_id'             => 'required|numeric|min:1'                
            ];
            
            $this->validate($request, $rules, $messages);

            $modelo = new Modelo;
            $modelo->nombre                       = $request->nombre;
            $modelo->marcas_id                    = $request->marcas_id;
            $modelo->usuario_id                   = Auth::user()->email;
            $modelo->created_at                   = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
            
            try {
                DB::beginTransaction();
                if($modelo->save()) {                    
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
        $data =Modelo::find($id);
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
        if (Auth::user()->is('red-frio|root') && Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            $data = Modelo::with('marca')->find($id);
            if($data) {               
                return view('catalogo.modelo.edit')->with(['data' => $data]); 
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

        if (Auth::user()->is('root|red-frio') && Auth::user()->can('update.catalogos') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha'
            ];

            $rules = [
                'nombre'                => 'required|min:3|max:100|string|unique:modelos,nombre,'.$id.',id,deleted_at,NULL',
                'marcas_id'             => 'required|numeric|min:1'                
            ];
            
            $this->validate($request, $rules, $messages);

            $modelo                = Modelo::find($id);
            $modelo->nombre        = $request->nombre;  
            $modelo->marcas_id     = $request->marcas_id;
            $modelo->usuario_id    = Auth::user()->email;
            $modelo->updated_at = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
            
            try {
                DB::beginTransaction();
                if($modelo->save()) {                    
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
        $modelo = Modelo::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->is('root|red-frio') && Auth::user()->can('delete.catalogos') && Auth::user()->activo==1) {
                try {                    
                    DB::beginTransaction();
                    $updates = DB::table('modelos')
                            ->where('id', '=', $id)
                            ->update(['deleted_at' => Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s'), 'usuario_id' => Auth::user()->email]);
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
