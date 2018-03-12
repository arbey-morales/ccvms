<?php

namespace App\Http\Controllers\Catalogo\RedFrio;

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
use App\Models\Catalogo\RedFrio\ContenedorBiologico;
use App\Models\Catalogo\RedFrio\Modelo;
use App\Models\Catalogo\RedFrio\Marca;
use App\Models\Catalogo\RedFrio\EstatusContenedor;
use App\Models\Catalogo\RedFrio\TipoContenedor;

class ContenedorBiologicoController extends Controller
{
    /**
	 * @api {get}   /catalogo/contenedor-biologico/     1. Listar Contenedores de biológico  
	 * @apiVersion  0.1.0
	 * @apiName     ContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
	 *
	 * @apiParam    {String}        q           Folio o serie del contenedor de biológico (Opcional).
     * @apiParam    {Number}        clues_id    Id de clue (Opcional).
     * @apiParam    {Request}       request     Cabeceras de la petición.
     *
     * @apiSuccess  {View}          index       Vista de ContenedorBiologico (Se omite si la petición es ajax).
     * @apiSuccess  {Json}          data
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": [{'id', 'clues_id', 'servidor_id', 'incremento', 'tipos_contenedores_id', 'modelos_id', 'estatus_contenedor_id', 'serie', 'folio', 'tipos_mantenimiento', 'temperatura_minima', 'temperatura_maxima', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}...]
	 *     } 
	 *
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 404 No encontrado
	 *     {
	 *       "icon"     :   String icono a utilizar en la vista,
     *       "error"    :   String número de error,
     *       "title"    :   String titulo del mensaje,
     *       "message"  :   String descripción del error
	 *     }
	 */
    public function index(Request $request)
    {
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|red-frio')) {
            $data = ContenedorBiologico::with('clue','tipoContenedor','modelo','estatus');
            if ($request['q']) {
                $data = Where('serie','LIKE',"%".$request['q']."%");
            } 
            
            if($request['clues_id']){
                $data = $data->where('clues_id',$request['clues_id']);
            }
            $data = $data->where('deleted_at',NULL)
                ->orderBy('clues_id', 'ASC')
                ->get();
            if ($request->ajax()) {
                return Response::json([ 'data' => $data], HttpResponse::HTTP_OK);
            } else {
                return view('catalogo.contenedor.index')->with('data', $data)->with('q', $request['q']);
            }            
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
	 * @api {get}   /catalogo/contenedor-biologico/create   2. Crear vista de nuevo Vontenedor de biológico
	 * @apiVersion  0.1.0
	 * @apiName     CreateContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
     * 
     * @apiSuccess  {View}       create               Vista alojada en: \resources\views\catalogo\contenedor-biologico\create
     * @apiSuccess  {Array}      clues                Arreglo del catálogo de clues
     * @apiSuccess  {Array}      modelos              Arreglo del catálogo de modelos
     * @apiSuccess  {Array}      tipos_contenedores   Arreglo del catálogo de tipos de contenedores
     * @apiSuccess  {Array}      estatus              Arreglo del catálogo de estatus   
     * 
     * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'code'    : 1,
     *       'title'   : 'Información',
     *       'text'    : 'Ocurrió un error al intentar eliminar los datos.',
     *       'type'    : 'error',
     *       'styling' : 'bootstrap3'
	 *     }
     */
	public function create()
    {
        if (Auth::user()->can('create.catalogos') && Auth::user()->is('root|red-frio') && Auth::user()->activo==1) {  
            //$clues   = Clue::select('id','clues','nombre')->where('instituciones_id',13)->where('deleted_at',NULL)->where('estatus_id', 1)->get();
            $tipos_contenedores  = TipoContenedor::where('deleted_at',NULL)->get();
            $modelos  = Modelo::where('deleted_at',NULL)->get();
            $estatus = EstatusContenedor::where('deleted_at',NULL)->get();
            $arrayestatu=[];
            $arraymodelo=[];
            foreach ($estatus as $estatu) {
                $arrayestatu[$estatu->id] = $estatu->descripcion;
            }
            foreach ($tipos_contenedores as $tipocontenedor) {
                $arraytipocontenedor[$tipocontenedor->id] = $tipocontenedor->nombre;
            }
           
            return view('catalogo.contenedor.create')->with(['estatus' => $arrayestatu, 'tipos_contenedores' => $arraytipocontenedor]);
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
	 * @api {post}  /catalogo/contenedor-biologico/store    3. Crear Contenedor de biológico
	 * @apiVersion  0.1.0
	 * @apiName     StoreContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
	 *
     * @apiParam    {Request}    request                                    Cabeceras de la petición.
	 *
	 * @apiSuccess  {View}       /catalogo/contenedor-biologico/create      Vista para crear nuevos Contenedores de biólogico
     * 
     * @apiSuccess  {String}     msgGeneral                                 Devuelve mensaje de éxito
     * @apiSuccess  {String}     type                                       Tipos válidos: success, error, warning e info
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'msgGeneral'   :   'Operación realizada con éxito',
     *       'type'         :   'success'
	 *     }
	 *
     * @apiError ContenedorBiologicoNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'msgGeneral'   :   'Ocurrió un error al intentar guardar los datos enviados.',
     *       'type'         :   'error'
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
                'date'     => 'El campo :attribute debe ser formato fecha',
                'in'       => 'El campo :attribute debe ser un valor permitido'
            ];

            $rules = [
                'clues_id'                => 'required|min:1|numeric',
                'modelos_id'              => 'required|min:1|numeric',
                'tipos_contenedores_id'   => 'required|min:1|numeric',
                'capacidad'               => 'required|min:1',
                'estatus_contenedores_id' => 'required|min:1|numeric',
                'temperatura_minima'      => 'sometimes|numeric',
                'temperatura_maxima'      => 'sometimes|numeric'
            ];

            if(trim($request->serie)=='N/A' || trim($request->serie)=='n/a'){
            } else {
                $rules['serie'] = 'required|min:1|max:25|unique:contenedores,serie,NULL,id,deleted_at,NULL';
            }
            
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
            $unidad_medida_id = 39; // CÁMARA FRÍA Y REFRI -> pie
            if ($request->tipos_contenedores_id==4) // TERMO -> lts
                $unidad_medida_id = 4;

            
            $contenedor = new ContenedorBiologico;
            $contenedor->id                         = $id;
            $contenedor->servidor_id                = $clue->servidor;
            $contenedor->incremento                 = $incremento;
            $contenedor->clues_id                   = $request->clues_id;
            $contenedor->modelos_id                 = $request->modelos_id;
            $contenedor->unidades_medidas_id        = $unidad_medida_id;
            $contenedor->tipos_contenedores_id      = $request->tipos_contenedores_id;
            $contenedor->capacidad                  = $request->capacidad;
            $contenedor->estatus_contenedor_id      = $request->estatus_contenedores_id;
            $contenedor->serie                      = $request->serie; 
            $contenedor->usuario_id                 = Auth::user()->email;
            $contenedor->created_at                 = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
            
            try {
                DB::beginTransaction();
                if($contenedor->save()) {                    
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
	 * @api {get}   /catalogo/contenedor-biologico/:id  4. Consultar Contenedor de biológico 
	 * @apiVersion  0.1.0
	 * @apiName     GetContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
	 *
     * @apiSuccess      {Json}      data        Contiene los detalles de la consulta de una Contenedores de biológico 
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'clues_id', 'servidor_id', 'incremento', 'tipos_contenedores_id', 'modelos_id', 'estatus_contenedor_id', 'serie', 'folio', 'tipos_mantenimiento', 'temperatura_minima', 'temperatura_maxima', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError ContenedorBiologicoNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
    public function show($id)
    {
        $data = ContenedorBiologico::with('clue','tipoContenedor','modelo','estatus')->find($id);
        if(!$data ){            
            return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
        }
        return response()->json([ 'data' => $data]);
    }

    /**
	 * @api {get}   /catalogo/contenedor-biologico/:id/edit     5. Editar Contenedor de biológico
	 * @apiVersion  0.1.0
	 * @apiName     EditContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
     * 
     * @apiParam    {Number}        id                  Contenedor de biológico id único.
     * 
     * @apiSuccess  {View}          edit                 Vista alojada en: \resources\views\catalogo\contenedor-biologico\edit
     * @apiSuccess  {Array}         clues                Arreglo del catálogo de clues
     * @apiSuccess  {Array}         modelos              Arreglo del catálogo de modelos
     * @apiSuccess  {Array}         tipos_contenedores   Arreglo del catálogo de tipos de contenedores
     * @apiSuccess  {Array}         estatus              Arreglo del catálogo de estatus  	 *
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'clues_id', 'servidor_id', 'incremento', 'tipos_contenedores_id', 'modelos_id', 'estatus_contenedor_id', 'serie', 'folio', 'tipos_mantenimiento', 'temperatura_minima', 'temperatura_maxima', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError ContenedorBiologicoNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
     public function edit($id)
     {
         if (Auth::user()->is('root|red-frio') && Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            $data = ContenedorBiologico::with('modelo','clue')->find($id);
            if($data) {                
                $tipos_contenedores  = TipoContenedor::where('deleted_at',NULL)->get();
                $estatus = EstatusContenedor::where('deleted_at',NULL)->get();
                foreach ($estatus as $estatu) {
                    $arrayestatu[$estatu->id] = $estatu->descripcion;
                }
                foreach ($tipos_contenedores as $tipocontenedor) {
                    $arraytipocontenedor[$tipocontenedor->id] = $tipocontenedor->nombre;
                }
               
                return view('catalogo.contenedor.edit')->with(['data' => $data, 'estatus' => $arrayestatu, 'tipos_contenedores' => $arraytipocontenedor]); 
            } else {
                return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
         } else {
             return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
         }
     }

    /**
	 * @api {PUT}   /catalogo/contenedor-biologic/update    6. Actualizar Contenedor de biológico 
	 * @apiVersion  0.1.0
	 * @apiName     UpdateContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
     * 
     * @apiParam    {Number}    id          Contenedor de biologico id único.
     * @apiParam    {Request}   request     Cabeceras de la petición.
	 
	 * @apiSuccess  {String}    msgGeneral  Devuelve mensaje de éxito
     * @apiSuccess  {String}    type        Tipos válidos: success, error, warning e info
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'msgGeneral'   :   'Operación realizada con éxito',
     *       'type'         :   'success'
	 *     }
	 *
     * @apiError ContenedorBiologicoNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'msgGeneral'   :   'Ocurrió un error al intentar guardar los datos enviados.',
     *       'type'         :   'error'
	 *     }
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
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe tener :min caracteres como mínimo',
                'max'      => 'El campo :attribute debe tener :max caracteres como máximo',
                'unique'   => 'El campo :attribute ya existe',
                'numeric'  => 'El campo :attribute debe ser un número.',
                'date'     => 'El campo :attribute debe ser formato fecha',
                'in'       => 'El campo :attribute debe ser un valor permitido'
            ];

            $rules = [
                'clues_id'                => 'required|min:1|numeric',
                'modelos_id'              => 'required|min:1|numeric',
                'tipos_contenedores_id'   => 'required|min:1|numeric',
                'capacidad'               => 'required|min:1',
                'estatus_contenedores_id' => 'required|min:1|numeric',
                'temperatura_minima'      => 'sometimes|numeric',
                'temperatura_maxima'      => 'sometimes|numeric'
            ];

            if(trim($request->serie)=='N/A' || trim($request->serie)=='n/a'){
            } else {
                $rules['serie'] = 'required|min:1|max:25|unique:contenedores,serie,'.$id.',id,deleted_at,NULL';
            }
            
            $this->validate($request, $rules, $messages);
            $request->clues_id = (int) $request->clues_id;
            $clue = Clue::find($request->clues_id);
            
            $unidad_medida_id = 39; // CÁMARA FRÍA Y REFRI -> pie
            if ($request->tipos_contenedores_id==4) // TERMO -> lts
                $unidad_medida_id = 4;
            
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
            $contenedor->modelos_id                 = $request->modelos_id;
            $contenedor->tipos_contenedores_id      = $request->tipos_contenedores_id;
            $contenedor->unidades_medidas_id        = $unidad_medida_id;
            $contenedor->estatus_contenedor_id      = $request->estatus_contenedores_id;
            $contenedor->serie                      = $request->serie;  
            $contenedor->capacidad                  = $request->capacidad;
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
                    $msgGeneral = 'Perfecto! se guardaron los datos';
                    $type       = 'flash_message_ok';
                    Session::flash($type, $msgGeneral);
                    return redirect('catalogo/red-frio/contenedor-biologico/'.$new_contenedor.'/edit');
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
	 * @api {DELETE}    /catalogo/contenedor-biologic/:id   7. Borrar Contenedor de biológico 
	 * @apiVersion  0.1.0
	 * @apiName     DestroyContenedorBiologico
	 * @apiGroup    Catalogo/Contenedor-Biologico
     * 
     * @apiParam    {Number}    id          ContenedorBiologico id único.
     * @apiParam    {Request}   request     Cabeceras de la petición.
	 
	 * @apiSuccess  {String}    msgGeneral  Mensaje descriptivo de la operación realizada
     * @apiSuccess  {String}    type        Tipos válidos: success, error, warning e info
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'code'    : 1,
     *       'title'   : 'Información',
     *       'text'    : 'Se borraron los datos',
     *       'type'    : 'success',
     *       'styling' : 'bootstrap3'
	 *     }
	 *
     * @apiError ContenedorBiologicoNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'code'    : 1,
     *       'title'   : 'Información',
     *       'text'    : 'Ocurrió un error al intentar eliminar los datos.',
     *       'type'    : 'error',
     *       'styling' : 'bootstrap3'
	 *     }
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
