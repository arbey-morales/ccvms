<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session;
use App\Catalogo\Ageb;
use App\Catalogo\Municipio;

class AgebController extends Controller
{
    /**
     * @api {get} /catalogo/ageb/:id Devuelve detalles de AGEB's
     * @apiName GetAgeb
     * @apiGroup Ageb
     *
     * @apiParam {String} id Agebs único.
     *
     * @apiSuccess {String} firstname Firstname of the Ageb.
     * @apiSuccess {String} lastname  Lastname of the Ageb.
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('show.catalogos') && Auth::user()->activo==1) {
            $parametros = Input::only('q','localidades_id'); 
            $data = DB::table('agebs as a')
            ->select('a.*','l.nombre as localidad','m.nombre as municipio')
            ->leftJoin('localidades as l','l.id','=','a.localidades_id')
            ->leftJoin('municipios as m','m.id','=','l.municipios_id')
            ->where('a.deleted_at',NULL)
            ->orderBy('a.id', 'ASC');
            if (Auth::user()->is('root|admin')) { } else {
                $data = $data->where('m.jurisdicciones_id', Auth::user()->idJurisdiccion);
            }  
            if ($parametros['q']) {
                $data = $data->where('a.id','LIKE',"%".$parametros['q']."%");
            }
            if ($parametros['localidades_id']) {
                $data = $data->where('a.localidades_id', $parametros['localidades_id']);
            }

            $data = $data->orderBy('localidad', 'ASC')->orderBy('a.id', 'ASC')->get();  
            if ($request->ajax()) {
                return response()->json([ 'data' => $data]);
            } else {      
                return view('catalogo.ageb.index')->with('agebs', $data);
            }
        } else {
            return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No tiene autorización para acceder al recurso. Se ha negado el acceso.'], 403);
        }
    }

    /**
	 * @api {get} /catalogo/ageb/ Devuelve detalles de AGEB's 
	 * @apiVersion 0.1.0
	 * @apiName GetAgeb
	 * @apiGroup Ageb
	 *
	 * @apiParam {Number} id Agebs unique ID.
	 *
	 * @apiSuccess {Number} code  Código 0 conforme todo ha ido bien.
	 * @apiSuccess {Bool} true/false  True o false dependiendo del resultado.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "code": 0,
	 *       "response": true
	 *     }
	 *
	 * @apiError AgebNotFound The id of the Ageb was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "AgebNotFound"
	 *     }
	 */
    public function show($id)
    {
        $data = Ageb::with('municipio','localidad')->find($id);           
        if(!$data ){            
            return response()->json(['error' => "No se encuentra el recurso que esta buscando."]);
        }
        return response()->json([ 'data' => $data]);
    }
}
