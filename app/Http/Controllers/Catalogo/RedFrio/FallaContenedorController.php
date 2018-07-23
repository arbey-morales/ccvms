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

use App\Models\Catalogo\RedFrio\FallaContenedor;

class FallaContenedorController extends Controller
{
    /**
	 * @api {get}   /catalogo/red-frio/falla-contenedor/     1. Listar fallas de Contenedores de biológico  
	 * @apiVersion  0.1.0
	 * @apiName     FallaContenedor
	 * @apiGroup    Catalogo/Red-Frio/Falla-Contenedor
	 *
	 * @apiParam    {String}        q           Folio o serie del contenedor de biológico (Opcional).
     * @apiParam    {Number}        clues_id    Id de clue (Opcional).
     * @apiParam    {Request}       request     Cabeceras de la petición.
     *
     * @apiSuccess  {View}          index       Vista de FallaContenedor (Se omite si la petición es ajax).
     * @apiSuccess  {Json}          data
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": [{"id":1,"descripcion":"Refrigerador no enfria","clave":"F1","created_at":null,"updated_at":null,"deleted_at":null}]
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
        $parametros = Input::only('q');
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1 && Auth::user()->is('root|red-frio')) {
            $data = FallaContenedor::where('deleted_at',NULL);
            if ($parametros['q']) {
                $data = $data->where('nombre','LIKE',"%".$parametros['q']."%");
            }  
            $data = $data->get(); 

            if ($request->ajax()) {
                return response()->json([ 'data' => $data]);
            } else {             
                return view('catalogo.falla-contenedor.index')->with('data', $data);
            }
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

        /**
	 * @api {get}   /catalogo/red-frio/falla-contenedor/create   2. Crear vista de nueva FallaContenedor
	 * @apiVersion  0.1.0
	 * @apiName     CreateFallaContenedor
	 * @apiGroup    Catalogo/Red-Frio/Falla-Contenedor
     * 
     * @apiSuccess  {View}    create                 Vista alojada en: \resources\views\catalogo\falla-contenedor\create   
     * 
     */
    public function create()
    {
        return view('catalogo.falla-contenedor.create');
    }

   /**
    * @api {post} /catalogo/red-frio/falla-contenedor/store     3. Crear Falla de contenedor
    * @apiVersion  0.1.0
    * @apiName     StoreFallaContenedor
    * @apiGroup    Catalogo/Red-Frio/Falla-Contenedor
    *
    * @apiParam    {Request}       request                     Cabeceras de la petición.
    *
    * @apiSuccess  {View}          /catalogo/red-frio/falla-contenedor/create             Vista para crear Falla Contenedor
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
                'descripcion'                => 'required|min:3|string|unique:fallas_contenedores,descripcion,NULL,id,deleted_at,NULL'                
            ];
            
            $this->validate($request, $rules, $messages);

            $data = new FallaContenedor;
            $data->descripcion                  = $request->descripcion;
            $data->clave                        = $request->clave;
            $data->created_at                   = Carbon::now("America/Mexico_City")->format('Y-m-d H:i:s');
            
            try {
                DB::beginTransaction();
                if($data->save()) {                    
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
	 * @api {get} 	/catalogo/red-frio/falla-contenedor/:id 	2. Consultar fallas de Contenedores
	 * @apiVersion 	0.1.0
	 * @apiName 	FallaContenedor
	 * @apiGroup 	Catalogo/Red-Frio/Falla-Contenedor
	 *
     * @apiSuccess 	{Json} 		data		Devuelve detalles una falla de contenedor
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {"id":1,"descripcion":"Refrigerador no enfria","clave":"F1","created_at":null,"updated_at":null,"deleted_at":null}
	 *     }
	 *
     * @apiError FallaContenedorNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
     public function show($id)
     {
         $data = FallaContenedor::find($id);
         if(!$data ){            
             return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
         }
         return response()->json([ 'data' => $data]);
     }
}
