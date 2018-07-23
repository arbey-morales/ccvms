<?php

namespace App\Http\Controllers\Catalogo\RedFrio;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, DB, Input, Session, Response;
use Carbon\Carbon;

use App\Models\Catalogo\RedFrio\TipoContenedor;

class TipoContenedorController extends Controller
{
    /**
	 * @api {get} 	/catalogo/red-frio/tipo-contenedor/ 	1. Listar Tipos Contenedores 
	 * @apiVersion 	0.1.0
	 * @apiName 	TipoContenedor
	 * @apiGroup 	Catalogo/Red-Frio/Tipo-Contenedor
	 *
	 * @apiParam 	{String} 		q 			Descripción de Tipo contenedor (Opcional).
     *
     * @apiSuccess 	{View} 			index  		Vista de Tipo contenedor (Se omite si la petición es ajax).
     * @apiSuccess 	{Json} 			data		Lista de Tipos de contenedores
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": [{"id":1,"nombre":"Refrigerador Solar","clave":"RS","imagen":"refrigerdor-solar.png","usuario_id":"frio@gmail.com","created_at":"2018-02-07 18:17:05","updated_at":null,"deleted_at":null}...]
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
        $data = TipoContenedor::where('deleted_at',NULL);
        if ($parametros['q']) {
            $data = $data->where('nombre','LIKE',"%".$parametros['q']."%");
        }  
        $data = $data->get(); 

        if ($request->ajax()) {
            return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "total" => count($data)), 200);
        } else {             
            return view('catalogo.marca.index')->with('data', $data);
        }
    }
    /**
	 * @api {get}   /catalogo/red-frio/tipo-contenedor/:id  4. Consultar Tipos Contenedores 
	 * @apiVersion  0.1.0
	 * @apiName     StoreTipoContenedor
	 * @apiGroup    Catalogo/Red-Frio/Tipo-Contenedor
	 *
	 *
	 * @apiSuccess  {View}       show       Vista de Tipo Contenedor(Se omite si la petición es ajax).
     * @apiSuccess  {Json}       data       Detalles de tipo contenedor en formato JSON
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'nombre', 'imagen', 'clave', 'usuario_id', 'created_at', 'updated_at', 'deleted_at'}
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
         $data = TipoContenedor::find($id);
         if(!$data ){            
             return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
         }
         return response()->json([ 'data' => $data]);
     }
 
}
