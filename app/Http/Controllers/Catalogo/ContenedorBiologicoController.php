<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use \Validator,\Hash, \Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;
use Carbon\Carbon;

use Session; 
use App\Catalogo\Clue;
use App\Catalogo\ContenedorBiologico;
use App\Catalogo\Modelo;
use App\Catalogo\Marca;
use App\Catalogo\EstatusContenedor;
use App\Catalogo\TipoContenedor;

class ContenedorBiologicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = Input::only('q','clues_id');
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|red-frio')) {
            $data = ContenedorBiologico::with('clue','tipoContenedor','marca','modelo','estatus');
            if ($parametros['q']) {
                $data = where('folio','LIKE',"%".$parametros['q']."%")
                    ->orWhere('serie','LIKE',"%".$parametros['q']."%");
            } 
            
            if($parametros['clues_id']){
                $data = $data->where('clues_id',$parametros['clues_id']);
            }
            $data = $data->where('deleted_at',NULL)
                ->orderBy('clues_id', 'ASC')
                ->get();
            if ($request->ajax()) {
                return Response::json([ 'data' => $data], HttpResponse::HTTP_OK);
            } else {
                return view('catalogo.contenedor.index')->with('data', $data)->with('q', $parametros['q']);
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
        if (Auth::user()->can('create.catalogos') && Auth::user()->is('root|red-frio') && Auth::user()->activo==1) {  
            $clues   = Clue::select('id','clues','nombre')->where('instituciones_id',13)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
            $marcas  = Marca::where('deleted_at',NULL)->get();
            $tipos_contenedores  = TipoContenedor::where('deleted_at',NULL)->get();
            $modelos  = Modelo::where('deleted_at',NULL)->get();
            $estatus = EstatusContenedor::where('deleted_at',NULL)->get();
            foreach ($estatus as $estatu) {
                $arrayestatu[$estatu->id] = $estatu->descripcion;
            }
            foreach ($modelos as $modelo) {
                $arraymodelo[$modelo->id] = $modelo->nombre;
            }
            foreach ($tipos_contenedores as $tipocontenedor) {
                $arraytipocontenedor[$tipocontenedor->id] = $tipocontenedor->nombre;
            }
            foreach ($marcas as $marca) {
                $arraymarca[$marca->id] = $marca->nombre;
            }
            foreach ($clues as $clue) {
                $arrayclue[$clue->id] = $clue->clues.' - '.$clue->nombre;
            }
           
            return view('catalogo.contenedor.create')->with(['clues' => $arrayclue, 'marcas' => $arraymarca, 'modelos' => $arraymodelo, 'estatus' => $arrayestatu, 'tipos_contenedores' => $arraytipocontenedor]);
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

        if (Auth::user()->is('root|red-frio') && Auth::user()->can('create.catalogos') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha',
                'in'       => 'El campo :attribute debe ser un valor permitido'
            ];

            $rules = [
                'clues_id'                => 'required|min:1|numeric',
                'marcas_id'               => 'required|min:1|numeric',
                'modelos_id'              => 'required|min:1|numeric',
                'tipos_contenedores_id'   => 'required|min:1|numeric',
                'tipos_mantenimiento'     => 'required|min:3|in:DIA,SEM,QUI,MES,IND',
                'estatus_contenedores_id' => 'required|min:1|numeric',
                'folio'                   => 'required|unique:contenedores,folio,NULL,id,deleted_at,NULL',
                'serie'                   => 'required|min:1|max:25|unique:contenedores,serie,NULL,id,deleted_at,NULL',
                'temperatura_minima'      => 'sometimes|numeric',
                'temperatura_maxima'      => 'sometimes|numeric'
            ];
            
            $this->validate($request, $rules, $messages);

            $id = '';
            $incremento = 0;
            $clue = Clue::find($request->clues_id);
            $contenedor_increment = ContenedorBiologico::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();

            if(count($contenedor_increment)>0){
                $incremento = $contenedor_increment[0]->incremento + 1; 
            } else {                 
                $incremento = 1;                             
            }
            $id = $clue->servidor.''.$incremento; 
            
            $contenedor = new ContenedorBiologico;
            $contenedor->id                         = $id;
            $contenedor->servidor_id                = $clue->servidor;
            $contenedor->incremento                 = $incremento;
            $contenedor->clues_id                   = $request->clues_id;
            $contenedor->marcas_id                  = $request->marcas_id;
            $contenedor->modelos_id                 = $request->modelos_id;
            $contenedor->tipos_contenedores_id      = $request->tipos_contenedores_id;
            $contenedor->tipos_mantenimiento        = $request->tipos_mantenimiento;
            $contenedor->estatus_contenedor_id      = $request->estatus_contenedores_id;
            $contenedor->serie                      = $request->serie;  
            $contenedor->folio                      = $request->folio;
            if(isset($request->temperatura_minima) && $request->temperatura_minima!="" && $request->temperatura_minima!=NULL)
                $contenedor->temperatura_minima     = $request->temperatura_minima;
            if(isset($request->temperatura_maxima) && $request->temperatura_maxima!="" && $request->temperatura_maxima!=NULL)
                $contenedor->temperatura_maxima     = $request->temperatura_maxima;
            $contenedor->usuario_id                 = Auth::user()->email;
            $contenedor->created_at                 = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
            
            try {
                DB::beginTransaction();
                if($contenedor->save()) {                    
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
        $data = ContenedorBiologico::with('clue','tipoContenedor','marca','modelo','estatus')->find($id);
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
         if (Auth::user()->is('root|red-frio') && Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            $data = ContenedorBiologico::findOrFail($id);
            if($data) {                
                $clues   = Clue::select('id','clues','nombre')->where('instituciones_id',13)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
                $marcas  = Marca::where('deleted_at',NULL)->get();
                $tipos_contenedores  = TipoContenedor::where('deleted_at',NULL)->get();
                $modelos  = Modelo::where('deleted_at',NULL)->get();
                $estatus = EstatusContenedor::where('deleted_at',NULL)->get();
                foreach ($estatus as $estatu) {
                    $arrayestatu[$estatu->id] = $estatu->descripcion;
                }
                foreach ($modelos as $modelo) {
                    $arraymodelo[$modelo->id] = $modelo->nombre;
                }
                foreach ($tipos_contenedores as $tipocontenedor) {
                    $arraytipocontenedor[$tipocontenedor->id] = $tipocontenedor->nombre;
                }
                foreach ($marcas as $marca) {
                    $arraymarca[$marca->id] = $marca->nombre;
                }
                foreach ($clues as $clue) {
                    $arrayclue[$clue->id] = $clue->clues.' - '.$clue->nombre;
                }
               
                return view('catalogo.contenedor.edit')->with(['data' => $data,'clues' => $arrayclue, 'marcas' => $arraymarca, 'modelos' => $arraymodelo, 'estatus' => $arrayestatu, 'tipos_contenedores' => $arraytipocontenedor]); 
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

        $contenedor = ContenedorBiologico::findOrFail($id);
        $contenedor_id = $id; // ESTE SERÍA EL ID SI LA CLUE NO CAMBIA
        $last_clue_id = $contenedor->clues_id; // ESTE SERÍA EL ID SI LA CLUE NO CAMBIA
        $created_at = $contenedor->created_at;
        $contenedor_original_id = $id; // ESTE SERÍA EL ID SI LA CLUE NO CAMBIA

        if (Auth::user()->is('root|red-frio') && Auth::user()->can('update.catalogos') && Auth::user()->activo==1) {
            $messages = [
                'required' => 'El campo :attribute es requirido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha',
                'in'       => 'El campo :attribute debe ser un valor permitido'
            ];

            $rules = [
                'clues_id'                => 'required|min:1|numeric',
                'marcas_id'               => 'required|min:1|numeric',
                'modelos_id'              => 'required|min:1|numeric',
                'tipos_contenedores_id'   => 'required|min:1|numeric',
                'tipos_mantenimiento'     => 'required|min:3|in:DIA,SEM,QUI,MES,IND',
                'estatus_contenedores_id' => 'required|min:1|numeric',
                'folio'                   => 'required|unique:contenedores,folio,'.$id.',id,deleted_at,NULL',
                'serie'                   => 'required|min:1|max:25|unique:contenedores,serie,'.$id.',id,deleted_at,NULL',
                'temperatura_minima'      => 'sometimes|numeric',
                'temperatura_maxima'      => 'sometimes|numeric'
            ];
            
            $this->validate($request, $rules, $messages);
            $request->clues_id = (int) $request->clues_id;
            $clue = Clue::find($request->clues_id);
            
            if($last_clue_id!=$request->clues_id){ // SI LAS CLUES NO COINCIDEN
                $contenedor_id = '';
                $incremento = 0;
                $contenedor_increment = ContenedorBiologico::where('servidor_id', $clue->servidor)->orderBy('incremento','DESC')->take(1)->get();

                if(count($contenedor_increment)>0){
                    $incremento = $contenedor_increment[0]->incremento + 1; 
                } else {                 
                    $incremento = 1;                             
                }
                $contenedor_id            = $clue->servidor.''.$incremento;
                $contenedor               = new ContenedorBiologico;
                $contenedor->id           = $contenedor_id;
                $contenedor->servidor_id  = $clue->servidor;                
                $contenedor->incremento   = $incremento;
            }
            $new_contenedor = $contenedor->id;
            
            $contenedor->clues_id                   = $request->clues_id;
            $contenedor->marcas_id                  = $request->marcas_id;
            $contenedor->modelos_id                 = $request->modelos_id;
            $contenedor->tipos_contenedores_id      = $request->tipos_contenedores_id;
            $contenedor->tipos_mantenimiento        = $request->tipos_mantenimiento;
            $contenedor->estatus_contenedor_id      = $request->estatus_contenedores_id;
            $contenedor->serie                      = $request->serie;  
            $contenedor->folio                      = $request->folio;
            if(isset($request->temperatura_minima) && $request->temperatura_minima!="" && $request->temperatura_minima!=NULL)
                $contenedor->temperatura_minima     = $request->temperatura_minima;
            if(isset($request->temperatura_maxima) && $request->temperatura_maxima!="" && $request->temperatura_maxima!=NULL)
                $contenedor->temperatura_maxima     = $request->temperatura_maxima;
            $contenedor->usuario_id                 = Auth::user()->email;
            $contenedor->created_at                 = $created_at;
            $contenedor->updated_at                 = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
            
            try {
                DB::beginTransaction();
                if($contenedor->save()) {
                    if($last_clue_id!=$request->clues_id){ // SI LAS CLUES NO COINCIDEN
                    $updates = DB::table('contenedores')
                        ->where('id', '=', $contenedor_original_id)
                        ->update(['deleted_at' => Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s')]);
                    }                    
                    DB::commit();
                    $msgGeneral = 'Perfecto! se gurdaron los datos';
                    $type       = 'flash_message_ok';
                    Session::flash($type, $msgGeneral);
                    return redirect('catalogo/contenedor-biologico/'.$new_contenedor.'/edit');
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
        $contenedor = ContenedorBiologico::findOrFail($id);
        if ($request->ajax()) {
            if (Auth::user()->is('root|red-frio') && Auth::user()->can('delete.catalogos') && Auth::user()->activo==1) {
                try {                    
                    DB::beginTransaction();
                    $updates = DB::table('contenedores')
                            ->where('id', '=', $id)
                            ->update(['deleted_at' => Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s')]);
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
