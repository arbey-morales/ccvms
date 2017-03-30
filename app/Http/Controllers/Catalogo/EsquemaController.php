<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Input;

use Session;
use App\Catalogo\Esquema;
use App\Catalogo\VacunaEsquema;

class EsquemaController extends Controller
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
             $data =  Esquema::where('descripcion','LIKE',"%".$parametros['q']."%")->with('vacunasEsquemas')->get();
        } else {
             $data =  Esquema::with('vacunasEsquemas')->get();
        }

        return view('catalogo.esquema.index')->with('esquemas', $data);
        //return Response::json([ 'data' => $data], HttpResponse::HTTP_OK);
        //return response()->json([ 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Esquema::with('vacunasEsquemas')->find($id);   
        if ($data) {
            return response()->json([ 'data' => $data]);
        } else {
           return response()->json([ 'data' => NULL]);
        }        
    }
}
