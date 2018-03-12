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

use App\Models\Catalogo\RedFrio\Marca;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = Input::only('q');
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|red-frio')) {
            $data = Marca::where('deleted_at',NULL);
            if ($parametros['q']) {
                $data = $data->where('nombre','LIKE',"%".$parametros['q']."%");
            }  
            $data = $data->get(); 

            if ($request->ajax()) {
                return response()->json([ 'data' => $data]);
            } else {             
                return view('catalogo.marca.index')->with('data', $data);
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
         return view('catalogo.marca.create');
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
                 'required' => 'El campo :attribute es requerido',
                 'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                 'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                 'unique'   => 'El campo :attribute ya existe',
                 'numeric'  => 'El campo :attribute debe ser un número.',
                 'date'     => 'El campo :attribute debe ser formato fecha'
             ];
 
             $rules = [
                 'nombre'                => 'required|min:3|max:100|string|unique:marcas,nombre,NULL,id,deleted_at,NULL'                
             ];
             
             $this->validate($request, $rules, $messages);
 
             $marca = new Marca;
             $marca->nombre                       = $request->nombre;
             $marca->usuario_id                   = Auth::user()->email;
             $marca->created_at                   = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
             
             try {
                 DB::beginTransaction();
                 if($marca->save()) {                    
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
         $data = Marca::find($id);
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
             $data = Marca::findOrFail($id);
             if($data) {               
                 return view('catalogo.marca.edit')->with(['data' => $data]); 
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
                 'required' => 'El campo :attribute es requerido',
                 'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                 'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                 'unique'   => 'El campo :attribute ya existe',
                 'numeric'  => 'El campo :attribute debe ser un número.',
                 'date'     => 'El campo :attribute debe ser formato fecha'
             ];
 
             $rules = [
                 'nombre'                => 'required|min:3|max:100|string|unique:marcas,nombre,'.$id.',id,deleted_at,NULL'                
             ];
             
             $this->validate($request, $rules, $messages);
 
             $marca                = Marca::find($id);
             $marca->nombre        = $request->nombre;  
             $marca->usuario_id    = Auth::user()->email;
             $marca->updated_at = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
             
             try {
                 DB::beginTransaction();
                 if($marca->save()) {                    
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
         $marca = Marca::findOrFail($id);
         if ($request->ajax()) {
             if (Auth::user()->is('root|red-frio') && Auth::user()->can('delete.catalogos') && Auth::user()->activo==1) {
                 try {                    
                     DB::beginTransaction();
                     $updates = DB::table('marcas')
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
