<?php

namespace App\Http\Controllers\Catalogo\RedFrio;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, DB, Input, Response, Session; 

use App\Models\Catalogo\RedFrio\EstatusContenedor;
use App\Models\Catalogo\RedFrio\ContenedorBiologico;

class EstatusContenedorController extends Controller
{
    /**
	 * @api {get} 	/catalogo/estatus-contenedor/ 	1. Listar posibles Estatus de contenedor 
	 * @apiVersion 	0.1.0
	 * @apiName 	EstatusContenedor
	 * @apiGroup 	Catalogo/Estatus-Contenedor
	 *
	 * @apiParam 	{String} 		q 			Descripción del Estatus del contenedor (Opcional).
     *
     * @apiSuccess 	{View} 			index  		Vista de estatus de contenedores (Se omite si la petición es ajax).
     * @apiSuccess 	{Json} 			data		Lista de estatus de contenedores
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": [{'id', 'descripcion', 'color', 'icono', 'created_at', 'updated_at', 'deleted_at'}...]
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
		if ($parametros['q']) {
			$data = EstatusContenedor::where('descripcion','LIKE',"%".$parametros['q']."%")->where('deleted_at',NULL)->get();
		} else {
			$data = EstatusContenedor::where('deleted_at',NULL)->get();
		}
		
		if ($request->ajax()) {
			return Response::json(array("status" => 200, "messages" => "Operación realizada con exito", "data" => $data, "total" => count($data)), 200);			
		} else {       
			return view('catalogo.estatus-contenedor.index')->with('data', $data)->with('q', $parametros['q']);
		}
    }

    /**
	 * @api {get} 	/catalogo/estatus-contenedor/:id 	2. Consultar Estatus para contenedor
	 * @apiVersion 	0.1.0
	 * @apiName 	GetEstatusContenedor
	 * @apiGroup 	Catalogo/EstatusContenedor
	 *
     * @apiSuccess 	{Json} 		data		Devuelve detalles de un estaus de contenedor
	 *
	 * @apiSuccessExample Ejemplo de respuesta exitosa:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "data": {'id', 'descripcion', 'color', 'icono', 'created_at', 'updated_at', 'deleted_at'}
	 *     }
	 *
     * @apiError EstatusContenedorNotFound No se encuentra
     * 
	 * @apiErrorExample Ejemplo de repuesta fallida:
	 *     HTTP/1.1 200 No encontrado
	 *     {
     *       "error": No se encuentra el recurso que esta buscando
	 *     }
	 */
    public function show($id)
    {
        $data =EstatusContenedor::find($id);
        if(!$data ){            
            return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
        }
        return response()->json([ 'data' => $data]);
    }
}
