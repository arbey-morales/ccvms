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
	 * @api {get} 	/catalogo/red-frio/modelo/ 	1. Listar Modelos 
	 * @apiVersion 	0.1.0
	 * @apiName 	Modelo
	 * @apiGroup 	Catalogo/Red-Frio/Modelo
	 *
	 * @apiParam 	{String} 		q 			Descripción de Modelo (Opcional).
     *
     * @apiSuccess 	{View} 			index  		Vista de Modelo (Se omite si la petición es ajax).
     * @apiSuccess 	{Json} 			data		Lista de Modelos
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": [{"id":1,"nombre":"RVBA-10","usuario_id":"frio@gmail.com","created_at":"2018-02-07 18:17:05","updated_at":null,"deleted_at":null}...]
	 *     } 
	 *
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 404 No encontrado
	 *     {
	 *       "icon"		: String icono a utilizar en la vista,
     *       "error"	: String número de error,
     *       "title"	: String titulo del mensaje,
     *       "message"	: String descripción del error
	 *     }
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
	 * @api {get}   /catalogo/red-frio/modelo/create   2. Crear vista de nueva Modelo
	 * @apiVersion  0.1.0
	 * @apiName     CreateModelo
	 * @apiGroup    Catalogo/Red-Frio/Modelo
     * 
     * @apiSuccess  {View}    create                 Vista alojada en: \resources\views\catalogo\marca\create   
     * 
     */
	public function create()
    {
        return view('catalogo.modelo.create');
    }

    /**
	 * @api {post}  /catalogo/red-frio/modelo/store     3. Crear Modelo
	 * @apiVersion  0.1.0
	 * @apiName     StoreModelo
	 * @apiGroup    Catalogo/Red-Frio/Modelo
	 *
     * @apiParam    {Request}       request                     Cabeceras de la petición.
	 *
	 * @apiSuccess  {View}          /catalogo/red-frio/modelo/create             Vista para crear Modelo
     * 
     * @apiSuccess  {String}        estatus                  Valores: info, success
     * @apiSuccess  {String}        titulo                   Titulo del mensaje
     * @apiSuccess  {String}        texto                    Mensaje descriptivo de la operación realizada
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'titulo'   :  'Perfecto!',
     *       'texto'    :  'Operación realizada con éxito',
     *       'estatus'  :  'success'
	 *     }
	 *
     * @apiError  {String}        estatus                  Valores: warning, error
     * @apiError  {String}        titulo                   Titulo del mensaje de error
     * @apiError  {String}        texto                    Mensaje descriptivo de la operación fallida
     * @apiError  PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 409 Conflicto
	 *     {
     *       'titulo'   :  'Error!',
     *       'texto'    :  'Operación fallida, -- Mensaje de error -- ',
     *       'estatus'  :  'error'
	 *     }
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
	 * @api {get}   /catalogo/red-frio/modelo/:id  4. Consultar Modelo 
	 * @apiVersion  0.1.0
	 * @apiName     StoreModelo
	 * @apiGroup    Catalogo/Red-Frio/Modelo
	 *
	 *
	 * @apiSuccess  {View}       show       Vista de Modelo(Se omite si la petición es ajax).
     * @apiSuccess  {Json}       data       Detalles de marca en formato JSON
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'nombre', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
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
	 * @api {get}   /catalogo/red-frio/modelo/:id/edit     5. Editar Modelo
	 * @apiVersion  0.1.0
	 * @apiName     EditModelo
	 * @apiGroup    Catalogo/Red-Frio/Modelo
     * 
     * @apiParam    {Number}    id  Modelo id único.
     * 
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'nombre', 'marcas_id', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error":   No se encuentra el recurso que esta buscando
	 *     }
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
	 * @api {PUT}   /catalogo/red-frio/modelo/update   6. Actualizar Modelo 
	 * @apiVersion  0.1.0
	 * @apiName     UpdateModelo
	 * @apiGroup    Catalogo/Red-Frio/Modelo
     * 
     * @apiParam    {Number}       id                      Modelo id único.
     * @apiParam    {Request}      request                 Cabeceras de la petición.
	 
	 * @apiSuccess  {String}        msgGeneral             Mensaje descriptivo de la operación realizada
     * @apiSuccess  {String}        type                   Tipos válidos: success, error, warning e info
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {	   
     *       'msgGeneral'   :   'Operación realizada con éxito',
     *       'type'         :   'success'
	 *     }
	 *
     * @apiError PersonaNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       'msgGeneral'   :  'Ocurrió un error al intentar guardar los datos enviados.',
     *       'type'         :  'error'
	 *     }
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
	 * @api {DELETE}    /catalogo/red-frio/modelo/:id  7. Borrar Modelo 
	 * @apiVersion  0.1.0
	 * @apiName     DestroyModelo
	 * @apiGroup    Catalogo/Red-Frio/Modelo
     * 
     * @apiParam    {Number}       id              Modelo id único.
     * @apiParam    {Request}      request         Cabeceras de la petición.
	 
	 * @apiSuccess  {String}       msgGeneral      Mensaje descriptivo de la operación realizada
     * @apiSuccess  {String}       type            Tipos válidos: success, error, warning e info
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
     * @apiError PersonaNotFound No se encuentra
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
