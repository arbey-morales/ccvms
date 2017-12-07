<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Input;
use \Validator,\Hash, \Response, \DB;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Catalogo\Jurisdiccion;

class JurisdiccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = Input::only('q');
        $data =  Jurisdiccion::with('entidad');
        if ($parametros['q']) {
             $data =  $data->where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%");
        }
        $data =  $data->get();
       
        return Response::json([ 'data' => $data], HttpResponse::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Jurisdiccion::with('entidad','municipios')->find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return response()->json([ 'data' => $data]);
    }
}
