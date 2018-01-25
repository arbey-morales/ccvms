<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Input;
use \Validator,\Hash, \Response, \DB;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Catalogo\Entidad;

class EntidadController extends Controller
{
    /**
	 * @api {get} 	/catalogo/entidad/ 	1. Listar Entidades federativas 
	 * @apiVersion 	0.1.0
	 * @apiName 	Entidad
	 * @apiGroup 	Catalogo/Entidad
	 *
	 * @apiParam 	{String} 		q 			Nombre o clave de Entidad (Opcional).
     *
     * @apiSuccess 	{View} 			index  		Vista de Entidad (Se omite si la petición es ajax).
     * @apiSuccess 	{Json} 			data		Lista de entidades federativas
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
    public function index()
    {
        $parametros = Input::only('q');
        
        if ($parametros['q']) {
             $data =  Entidad::where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->orderBy('nombre', 'ASC')->get();
        } else {
             $data =  Entidad::orderBy('nombre', 'ASC')->get();
        }
       
        return Response::json([ 'data' => $data], HttpResponse::HTTP_OK);
    }

    /**
	 * @api {get} 	/catalogo/entidad/:id 	2. Consultar Entidad federativa
	 * @apiVersion 	0.1.0
	 * @apiName 	GetEntidad
	 * @apiGroup 	Catalogo/Entidad
	 *
     * @apiSuccess 	{Json} 		data		Devuelve detalles de una entidad federativa
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'clave', 'nombre', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError EntidadNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
    public function show($id)
    {
        $data = Entidad::find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);
    }
}
