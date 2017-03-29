<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Input;
use \Validator,\Hash, \Response, \DB;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Catalogo\Institucion;

class InstitucionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = Input::only('q');
        
        if ($parametros['q']) {
             $data =  Institucion::where('clave','LIKE',"%".$parametros['q']."%")->orWhere('nombreCorto','LIKE',"%".$parametros['q']."%")->orWhere('nombre','LIKE',"%".$parametros['q']."%")->get();
        } else {
             $data =  Institucion::all();
        }
       
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
        $data = Institucion::find($id);        
        
        if(!$data ){            
            return Response::json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([ 'data' => $data ], HttpResponse::HTTP_OK);
    }
}