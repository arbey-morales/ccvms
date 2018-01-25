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
use App\Catalogo\CodigoCenso;

class CodigoCensoController extends Controller
{
    /**
	 * @api {get} 	/catalogo/codigo/ 	1. Listar Códigos del censo nominal 
	 * @apiVersion 	0.1.0
	 * @apiName 	Codigo
	 * @apiGroup 	Catalogo/Codigo
	 *
	 * @apiParam 	{String} 		q 			Nombre o clave de Codigo (Opcional).
     * @apiParam 	{Request} 		request 	Cabeceras de la petición.
     *
     * @apiSuccess 	{View} 			index  		Vista de Codigo (Se omite si la petición es ajax).
     * @apiSuccess 	{Json} 			data		Lista de códigos de censo nominal
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": [{'id', 'clave', 'nombre', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}...]
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
        $parametros = Input::only('q');
        $data = CodigoCenso::where('deleted_at', NULL)->orderBy('clave', 'ASC');
        if ($parametros['q']) {
             $data =  $data->where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%");
        }       
        $data = $data->get();
        return response()->json([ 'data' => $data]);
    }

    /**
	 * @api {get} 	/catalogo/codigo/:id 	2. Consultar Código de censo nominal 
	 * @apiVersion 	0.1.0
	 * @apiName 	GetCodigo
	 * @apiGroup 	Catalogo/Codigo
	 *
     * @apiSuccess 	{Json} 		data		Devuelve detalles de un código del censo nominal
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'clave', 'nombre', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError CodigoNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
    public function show($id)
    {
        $data = Codigo::find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);
    }
}
